function fetchCentralityMeasure() {
    $("#table-centrality-measure")
        .DataTable({
            bDestroy: true,
            serverSide: true,
            processing: true,
            responsive: true,
            autoWidth: false,
            paging: true,
            info: false,
            order: [[0, "asc"]],
            ajax: {
                type: "POST",
                url: `${BASE_URL}/api/v1/datasnap/centrality_measure`,
                data: function (d) {
                    return d;
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("token", $.cookie("jwt_token"));
                },
            },
            columns: [
                { data: "no", width: "10%" },
                { data: "username" },
                { data: "degree_centrality", width: "15%" },
                { data: "betweenness_centrality", width: "15%" },
                { data: "closeness_centrality", width: "15%" },
                { data: "eigenvector_centrality", width: "15%" },
            ],
            columnDefs: [
                {
                    targets: [0, 1, 2, 3, 4, 5],
                    className: "text-center align-middle",
                },
            ],
        })
        .buttons()
        .container()
        .appendTo("#table-centrality-measure-wrapper .col-md-6:eq(0)");
}
fetchCentralityMeasure()

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
fetchNodesEdges();

function showVisualization(jsonNodesEdges) {
    Promise.all([
        jsonNodesEdges,
        null, // Jika ada data lain, tambahkan di sini.
    ]).then(function ([rawGraph, centrality]) {
        // Transform data JSON ke format yang sesuai
        const graph = {
            nodes: rawGraph.nodes.map((node) => ({ id: node })),
            edges: rawGraph.edges.map(([source, target]) => ({ source, target })),
        };

        const width = 1600;
        const height = 800;

        // Create SVG element for the graph
        const svg = d3
            .select("#graph-container")
            .append("svg")
            .attr("width", width)
            .attr("height", height);

        // Set up force simulation
        const simulation = d3
            .forceSimulation(graph.nodes)
            .force(
                "link",
                d3
                    .forceLink(graph.edges)
                    .id((d) => d.id)
                    .distance(100)
            )
            .force("charge", d3.forceManyBody().strength(-300))
            .force("center", d3.forceCenter(width / 2, height / 2))
            .force("x", d3.forceX().strength(0.1))
            .force("y", d3.forceY().strength(0.1))
            .on("tick", ticked);

        // Add links
        const link = svg
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

        // Add nodes
        const node = svg
            .append("g")
            .attr("class", "nodes")
            .selectAll("circle")
            .data(graph.nodes)
            .enter()
            .append("circle")
            .attr("class", "node")
            .attr("r", 5)
            .on("mouseover", function (event, d) {
                d3.select(this).attr("r", 8);
                d3.select(`#label-${d.id}`).style("visibility", "visible");
            })
            .on("mouseout", function (event, d) {
                d3.select(this).attr("r", 5);
                d3.select(`#label-${d.id}`).style("visibility", "hidden");
            })
            .call(
                d3
                    .drag()
                    .on("start", dragstarted)
                    .on("drag", dragged)
                    .on("end", dragended)
            );

        // Add labels with links
        const label = svg
            .append("g")
            .attr("class", "labels")
            .selectAll("text")
            .data(graph.nodes)
            .enter()
            .append("a")
            .attr("xlink:href", (d) => `https://www.instagram.com/${d.id}/`)
            .attr("target", "_blank")
            .append("text")
            .attr("class", "label")
            .attr("id", (d) => `label-${d.id}`)
            .text((d) => d.id);

        function ticked() {
            link
                .attr("x1", (d) => Math.max(0, Math.min(width, d.source.x)))
                .attr("y1", (d) => Math.max(0, Math.min(height, d.source.y)))
                .attr("x2", (d) => Math.max(0, Math.min(width, d.target.x)))
                .attr("y2", (d) => Math.max(0, Math.min(height, d.target.y)));

            node
                .attr("cx", (d) => Math.max(0, Math.min(width, d.x)))
                .attr("cy", (d) => Math.max(0, Math.min(height, d.y)));

            label
                .attr("x", (d) => Math.max(0, Math.min(width, d.x + 10)))
                .attr("y", (d) => Math.max(0, Math.min(height, d.y + 3)));
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