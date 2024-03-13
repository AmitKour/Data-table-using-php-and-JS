<!DOCTYPE html>
<html lang="en">
<head>
    <title>Contact Numbers</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <style>
        body {
            padding: 20px;
        }

        .contact-container {
            max-width: 400px;
            margin: 20px 0;
        }

        .btn-action {
            margin-right: 5px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .contact-item input {
            flex: 1;
            margin-right: 5px;
        }

        .delete-btn {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1 class="mb-4">Contact Numbers</h1>

    <div class="contact-container">
        <button onclick="addContact()" class="btn btn-primary btn-action">Add Contact</button>
        <div id="contact-list"></div>
    </div>

    <script>
        function addContact() {
            var contactList = document.getElementById("contact-list");

            var contactItem = document.createElement("div");
            contactItem.className = "contact-item";

            var input = document.createElement("input");
            input.type = "text";
            input.name = "contact[]"; // Use an array for multiple contacts
            input.placeholder = "Enter contact number";
            input.className = "form-control";

            var deleteBtn = document.createElement("span");
            deleteBtn.innerHTML = "&times;"; // "×" character
            deleteBtn.className = "delete-btn";
            deleteBtn.onclick = function() {
                contactList.removeChild(contactItem);
            };

            contactItem.appendChild(input);
            contactItem.appendChild(deleteBtn);

            contactList.appendChild(contactItem);
        }
    </script>
</body>
</html>
