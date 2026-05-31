 // Function to generate children up to a specific depth
        function generateChildren(prefix, depth, currentDepth = 1, index = 0) {
            if (currentDepth > depth) return [];
            const numChildren = 2; // Each node has 2 children
            const children = [];
            for (let i = 0; i < numChildren; i++) {
                const id = `${prefix}${generateIdentifier(index + i)}`;
                children.push({
                    id,
                    name: `Person ${id}`,
                    children: generateChildren(id, depth, currentDepth + 1, index * numChildren + i)
                });
            }
            return children;
        }

        
        // Function to break long names into lines
        function breakLongName(name) {
            if (name.length <= 15) return name;
            const parts = [];
            for (let i = 0; i < name.length; i += 15) {
                parts.push(name.slice(i, i + 15));
            }
            return parts.join('<br>');
        }

         // Function to render the tree recursively
         function renderTree(node, depth = 0) {
                if (!node) return '';
                if (depth > 3) return '';

                const children = node.children.map(child => renderTree(child, depth + 1)).join('');
                const hasChildren = node.children.length > 0;
                
                // Correct way to handle images
                const images = ['rf3.png', 'rf4.png', 'rf5.png'];
                const randomImage = images[Math.floor(Math.random() * images.length)];
                
                // Use the correct path to your images
                return `
                    <li>
                        <div class="text-black bg-white" data-id="${node.id}">
                            <img src="/image/${randomImage}" alt="Person Icon"><br>
                            ${breakLongName(node.name)}
                        </div>
                        ${hasChildren ? `<ul>${children}</ul>` : ''}
                    </li>
                `;
        }

         // Function to find a node by ID
         function findNode(id, currentNode) {
            if (currentNode.id === id) return currentNode;
            for (let child of currentNode.children) {
                const result = findNode(id, child);
                if (result) return result;
            }
            return null;
        }

        
        // Function to update the tree view based on the selected node
        function updateTree(nodeId) {
            const rootNode = findNode(nodeId, treeData);
            const treeHtml = renderTree(rootNode);
            document.getElementById('tree').innerHTML = `<ul>${treeHtml}</ul>`;

            // Show the back button if navigating away from the root
            document.getElementById('backButton').style.display = 'hidden';

            // Add click event listeners to each node
            document.querySelectorAll('.tree div').forEach(div => {
                div.addEventListener('click', (e) => {
                    e.stopPropagation();
                    navigateToNode(div.dataset.id);
                });
            });
        }


          // History management
          const historyStack = [];

// Function to navigate to a node and update the history stack
function navigateToNode(nodeId) {
    historyStack.push(nodeId);
    updateTree(nodeId);
}

        // Initial tree data structure with 20 stages
      

       
       

      

        // Function to go back to the previous node
        function goBack() {
            if (historyStack.length > 1) {
                historyStack.pop(); // Remove current node
                const previousNodeId = historyStack.pop(); // Get previous node ID
                updateTree(previousNodeId);
                // Show or hide the back button based on history
                document.getElementById('backButton').style.display = historyStack.length > 1 ? 'block' : 'none';
            } else {
                // If only one item in history, go back to the root
                updateTree('A');
                historyStack.length = 0; // Clear history
                document.getElementById('backButton').style.display = 'none';
            }
        }

        // Add click event listener to the back button
        document.getElementById('backButton').addEventListener('click', goBack);

        // Initial render with root node
        // updateTree();
    </script>


<script>

    // modal
    function openModal() {
    document.getElementById('userModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('userModal').classList.add('hidden');
}

// Optional: Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('userModal');
    if (event.target === modal) {
        closeModal();
    }
});


    function copyToClipboard(elementId) {
        var copyText = document.getElementById(elementId);
            copyText.select();
            copyText.setSelectionRange(0, 99999);
                  document.execCommand("copy");
                                  
        // Optional: Show a tooltip or notification that the text was copied
         alert("Copied the link!");
       }
    
 </script>