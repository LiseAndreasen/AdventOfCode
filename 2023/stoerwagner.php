<?php

include "Heap.php";

function add_edge(&$G, $u, $v, $w) {
    $G[$u][$v] = $w;
    $G[$v][$u] = $w;
}

function remove_node(&$G, $u) {
    foreach($G[$u] as $v => $w) {
        unset($G[$v][$u]);
    }
    unset($G[$u]);
}

function print_g($G) {
    foreach($G as $u => $edges) {
        printf("%s => ", $u);
        foreach($edges as $v => $w) {
            printf("%s (%d) ", $v, $w);
        }
        print("\n");
    }
}

/*
Stoer-Wagner minimum cut algorithm, based on Python version:

__author__ = 'ysitu <ysitu@users.noreply.github.com>'
Copyright (C) 2014
ysitu <ysitu@users.noreply.github.com>
All rights reserved.
BSD license.

assumptions:
graph not directed
graph connected
all weights are non-negative
more than 1 node

Returns the weighted minimum edge cut using the Stoer-Wagner algorithm.

Determine the minimum edge cut of a connected graph using the
Stoer-Wagner algorithm. In weighted cases, all weights must be
nonnegative.

Parameters
----------
G : graph
    Edges of the graph are expected to have an attribute named by the
    weight parameter below. If this attribute is not present, the edge is
    considered to have unit weight.

weight : string
    Name of the weight attribute of the edges. If the attribute is not
    present, unit weight is assumed. Default value: 'weight'.

Returns
-------
cut_value : integer or float
    The sum of weights of edges in a minimum cut.

partition : pair of node lists
    A partitioning of the nodes that defines a minimum cut.

G[start][end] = weight - going both ways
*/

function stoer_wagner($G) {
	$n = sizeof($G);
	if($n < 2) {
		print("graph has less than two nodes");
		return false;
	}

	$INT_MAX = 0x7FFFFFFF;
	$cut_value = $INT_MAX;
	foreach($G as $u => $v) {
	    $nodes[$u] = $u;
	}
	$contractions = []; // contracted node pairs
	
	if(1000 < $n) {
	    print("$n");
	}
	// Repeatedly pick a pair of nodes to contract until only one node is left.
	for($i=0;$i<$n-1;$i++) {
	    if(1000 < $n) { // progress
    	    if($i % 50 == 0) {
    	        printf("\n%4d ", $i);
    	    }
    	    print("*");
	    }
	    // Pick an arbitrary node u and create a set A = {u}.
	    $u = array_key_first($G);
	    $A = [];
	    $A[$u] = $u;
	    // Repeatedly pick the node "most tightly connected" to A and add it to
	    // A. The tightness of connectivity of a node not in A is defined by the
	    // sum of weights of edges connecting it to nodes in A.
	    $h = new Heap(); // min-heap emulating a max-heap
	    $edges = $G[$u];
	    foreach($edges as $v => $w) {
	        $h->insert([$v, - $w]);
	    }
	    // Repeat until all but one node has been added to A.
	    while(sizeof($A) < $n - $i - 1) {
	        [$u, $w] = $h->pop();
	        if(isset($A[$u])) {
	            // u might have been inserted in the heap more than once
	            continue;
	        }
	        $A[$u] = $u;
            foreach($G[$u] as $v => $ew) {
	            if(!isset($A[$v])) {
	                [$vv, $vw] = $h->get($v, 0);
	                $h->insert([$v, $vw - $ew]);
	            }
	        }
	    }
	    // A and the remaining node v define a "cut of the phase". There is a
	    // minimum cut of the original graph that is also a cut of the phase.
	    // Due to contractions in earlier phases, v may in fact represent
	    // multiple nodes in the original graph.
	    [$v, $w] = $h->pop();
	    while(isset($A[$v])) {
	        [$v, $w] = $h->pop();
	    }
	    $w = - $w;
	    if($w < $cut_value) {
	        $cut_value = $w;
	        $best_phase = $i;
	    }
	    // Contract v and the last node added to A.
	    $contractions[$i] = [$u, $v];
	    foreach($G[$v] as $w => $ew) {
	        if(strcmp($w, $u) != 0) {
	            if(!isset($G[$u][$w])) {
	                add_edge($G, $u, $w, $ew);
	            } else {
	                $G[$u][$w] += $ew;
	                $G[$w][$u] += $ew;
	            }
	        }
	    }
        remove_node($G, $v);
	}
	foreach($contractions as $edge) {
	    [$u, $v] = $edge;
	}
	
	// Recover the optimal partitioning from the contractions.
	$G = [];
	foreach($contractions as $u => $edge) {
	    if($u == $best_phase) {
	        break;
	    }
	    [$v, $w] = $edge;
	    add_edge($G, $v, $w, 1);
	}
	$reachable_size = 0;
	$v = $contractions[$best_phase][1];
	$reachable[$v] = $v;
	while($reachable_size < sizeof($reachable)) {
	    $reachable_size = sizeof($reachable);
	    foreach($reachable as $u1) {
	        if(!isset($G[$u1])) {
	            continue;
	        }
	        foreach($G[$u1] as $v1 => $w1) {
	            if(!isset($reachable[$v1])) {
	                $reachable[$v1] = $v1;
	            }
	        }
	    }
	}
	$unreachable = array_diff($nodes, $reachable);
	return [$cut_value, [$reachable, $unreachable]];
}
/*
$G = [];
add_edge($G, 'x', 'a', 3);
add_edge($G, 'x', 'b', 1);
add_edge($G, 'a', 'c', 3);
add_edge($G, 'b', 'c', 5);
add_edge($G, 'b', 'd', 4);
add_edge($G, 'd', 'e', 2);
add_edge($G, 'c', 'y', 2);
add_edge($G, 'e', 'y', 3);

[$cut_value, $partition] = stoer_wagner($G);
print("cut value " . $cut_value . "\n");
print_r($partition);
*/
?>