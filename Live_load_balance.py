import subprocess
import matplotlib.pyplot as plt
from collections import Counter
import time
import re

# List of backends
backends = ["hospital1", "hospital2", "hospital3", "hospital4"]
counts = Counter({b: 0 for b in backends})

plt.ion()  # interactive mode
fig, ax = plt.subplots(figsize=(8, 5))

# Add extra margins all around
plt.subplots_adjust(left=0.12, right=0.95, top=0.88, bottom=0.15)

# Professional color palette
colors = ["#1abc9c", "#3498db", "#f39c12", "#e74c3c"]

def update_chart():
    ax.clear()
    
    # Dynamic y-axis to add margin above highest bar
    max_count = max(counts.values()) if counts else 1
    ax.set_ylim(0, max_count * 1.2 + 1)  # add 20% margin on top
    
    bars = ax.bar(counts.keys(), counts.values(), color=colors, edgecolor="black", linewidth=1.2)

    # Add labels on top of bars
    for bar in bars:
        yval = bar.get_height()
        ax.text(bar.get_x() + bar.get_width() / 2, yval + 0.1 * max_count, int(yval),
                ha='center', va='bottom', fontsize=9, fontweight='bold')

    ax.set_ylabel("Number of Requests", fontsize=11)
    ax.set_title("NGINX Load Balancing Visualization (Live)", fontsize=13, fontweight="bold")
    ax.grid(axis="y", linestyle="--", alpha=0.7)
    plt.pause(0.1)

print("Starting continuous load balancing visualization... (Press Ctrl+C to stop)")

try:
    while True:
        # Run curl and capture output
        result = subprocess.run(
            ["curl", "-sk", "https://localhost/"],
            stdout=subprocess.PIPE,
            text=True
        )
        
        # Extract backend name using regex
        match = re.search(r"Hello from : (\w+)", result.stdout)
        if match:
            backend = match.group(1)
            if backend in backends:
                counts[backend] += 1
        
        # Update the bar chart
        update_chart()
        time.sleep(0.2)  # adjust speed (lower = faster traffic)

except KeyboardInterrupt:
    print("\nStopped visualization.")

plt.ioff()
plt.show()
