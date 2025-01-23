
function finishProgress() {
    updateProgressStatus('completed')
    window.location.href = `${BASE_URL}/dashboard/overview`;
}

function generateNodesEdges() {
    showLoadingSpinner();
    updateTab('tab_node_graph', 'ongoing')

    $.ajax({
        url: `${BASE_URL}/api/v1/upload_data/generate_nodes_edges`,
        type: "GET",
        headers: {
            token: $.cookie("jwt_token"),
        },
        success: function (response) {
            hideLoadingSpinner();
            $(`#tab_node_graph .btn-primary`).attr('onclick', `finishProgress()`);
            $(`#tab_node_graph .btn-primary`).prop('disabled', false);
            showToast("generate nodes & edges <b>successfully</b>", "success");
            $("#tab_node_graph_value").val('finish')
            updateTab('tab_node_graph', 'finish')
            checkLinkTab('linktab_node_graph');
            fetchNodesEdges();
        },
        error: function (xhr, status, error) {
            hideLoadingSpinner();
            console.log("ERROR scrapeMutualFollowers", error);
            showToast("gagal men-generate nodes & ednges", "danger");
        },
    });
}

if ($("#tab_node_graph_value").val() === "finish") {
    fetchNodesEdges();
}

function fetchNodesEdges() {
    $.ajax({
        url: `${BASE_URL}/api/v1/datasnap/nodes_edges`,
        type: "GET",
        headers: {
            token: $.cookie("jwt_token"),
        },
        success: function (response) {
            showVisualization(response.data)
        },
        error: function (xhr, status, error) {
            console.log("ERROR scrapeMutualFollowers", error);
            showToast("gagal mendapatkan nodes & ednges", "danger");
        },
    });
}

function showVisualization(jsonNodesEdges) {
    Promise.all([
        jsonNodesEdges,
        null,
    ]).then(function ([rawGraph, centrality]) {
        const graph = {
            nodes: rawGraph.nodes.map((node) => ({ id: node, degree: 0 })),
            edges: rawGraph.edges.map(([source, target]) => ({ source, target })),
        };

        // Hitung derajat untuk setiap node
        graph.edges.forEach((edge) => {
            const sourceNode = graph.nodes.find((node) => node.id === edge.source);
            const targetNode = graph.nodes.find((node) => node.id === edge.target);
            if (sourceNode) sourceNode.degree += 1;
            if (targetNode) targetNode.degree += 1;
        });

        const width = 1600;
        const height = 800;

        const svg = d3
            .select("#graph-container")
            .append("svg")
            .attr("width", width)
            .attr("height", height)
            .call(d3.zoom().on("zoom", (event) => {
                svgGroup.attr("transform", event.transform);
            }));

        const svgGroup = svg.append("g");

        const simulation = d3
            .forceSimulation(graph.nodes)
            .force("link", d3.forceLink(graph.edges).id((d) => d.id).distance(150))
            .force("charge", d3.forceManyBody().strength(-500))
            .force("center", d3.forceCenter(width / 2, height / 2))
            .on("tick", ticked);

        const link = svgGroup
            .append("g")
            .attr("class", "edges")
            .selectAll("line")
            .data(graph.edges)
            .enter()
            .append("line")
            .attr("class", "link")
            .style("stroke", "#999")
            .style("stroke-opacity", 0.6)
            .style("stroke-width", 1);

        const node = svgGroup
            .append("g")
            .attr("class", "nodes")
            .selectAll("circle")
            .data(graph.nodes)
            .enter()
            .append("circle")
            .attr("class", "node")
            .attr("r", 5)
            .style("fill", "steelblue")
            .on("mouseover", function (event, d) {
                d3.select(this).attr("r", 8);
            })
            .on("mouseout", function (event, d) {
                d3.select(this).attr("r", 5);
            })
            .call(
                d3
                    .drag()
                    .on("start", dragstarted)
                    .on("drag", dragged)
                    .on("end", dragended)
            );

        // Add labels with dynamic font size based on degree
        const label = svgGroup
            .append("g")
            .attr("class", "labels")
            .selectAll("text")
            .data(graph.nodes)
            .enter()
            .append("text")
            .attr("class", "label")
            .style("font-size", (d) => `${Math.max(10, d.degree + 5)}px`) // Font size based on degree
            .style("fill", "#555")
            .attr("text-anchor", "middle")
            .attr("dy", -10) // Position above the node
            .text((d) => d.id);

        function ticked() {
            link
                .attr("x1", (d) => d.source.x)
                .attr("y1", (d) => d.source.y)
                .attr("x2", (d) => d.target.x)
                .attr("y2", (d) => d.target.y);

            node.attr("cx", (d) => d.x).attr("cy", (d) => d.y);

            label
                .attr("x", (d) => d.x)
                .attr("y", (d) => d.y - 10); // Keep label above node
        }

        function dragstarted(event, d) {
            if (!event.active) simulation.alphaTarget(0.3).restart();
            d.fx = d.x;
            d.fy = d.y;
        }

        function dragged(event, d) {
            d.fx = event.x;
            d.fy = event.y;
        }

        function dragended(event, d) {
            if (!event.active) simulation.alphaTarget(0);
            d.fx = null;
            d.fy = null;
        }
    });
}
