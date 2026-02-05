import networkx as nx
from stoerwagner import *

def parse_puzzle(puzzle):
    L = []
    for line in puzzle.splitlines():
        node, others = line.split(": ")
        L.append((node, others.split()))
    return L

with open("d25input2.txt") as fh:
    data = fh.read()

G = nx.Graph()
for node, others in parse_puzzle(data):
    for other in others:
        G.add_edge(node, other, weight=1)

cut_value, partition = nx.stoer_wagner(G)
print("Minimum cut: ", cut_value)
l0 = len(partition[0])
l1 = len(partition[1])
print("Sizes of partitions multiplied: ", l0, "x", l1, "=", l0 * l1)

