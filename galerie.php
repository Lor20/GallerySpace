<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Image Gallery</title>
    <style>
        .heading {
            width: 90%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
            margin: 20px auto;
        }

        .heading h1 {
            font: 50px;
            color: #4c4c4c;
            margin-bottom: 25px;
            position: relative;
        }

        .heading h1::after {
            content: " ";
            position: absolute;
            width: 100%;
            height: 4px;
            display: block;
            margin: 0 auto;
            background-color: #4c4c4c;
        }

        .gallery {
            display: flex;
            flex-wrap: wrap;
        }

        .gallery img {
            width: 200px;
            height: 150px;
            object-fit: cover;
            margin: 10px;
            cursor: pointer;
        }

        .fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .fullscreen img {
            max-width: 90%;
            max-height: 90%;
        }

        .navigation {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 999;
        }

        .prev,
        .next {
            padding: 10px;
            background-color: #ccc;
            border: none;
            cursor: pointer;
            outline: none;
            font-size: 16px;
        }

        .prev {
            left: 10px;
        }

        .next {
            right: 10px;
        }
    </style>
</head>

<body>
    <div class="heading">
        <h1>GallerySpace</h1>

    </div>
    <div class="gallery">
        <?php
        // Database configuration
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "galerie";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Query to fetch images from database
        $query = "SELECT * FROM images";
        $result = mysqli_query($conn, $query);

        // Fetch images and display
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<img src="' . $row['chemin'] . '" onclick="showFullscreen(\'' . $row['chemin'] . '\')" />';
        }

        // Close connection
        mysqli_close($conn);
        ?>
    </div>
    

    <script>
        function showFullscreen(imageSrc) {
            const fullscreen = document.createElement("div");
            fullscreen.classList.add("fullscreen");
            const image = document.createElement("img");
            image.src = imageSrc;
            image.classList.add("zoomable"); // Ajoutez la classe 'zoomable' à l'image
            fullscreen.appendChild(image);
            document.body.appendChild(fullscreen); 
            fullscreen.addEventListener("click", () => {
                fullscreen.remove();
            });

            // Ajouter la logique de zoom
            let scale = 1;
            const scaleStep = 0.1;
            const maxScale = 2;

            image.addEventListener('wheel', function(event) {
                if (event.deltaY < 0) {
                    if (scale < maxScale) {
                        scale += scaleStep;
                        if (scale > maxScale) {
                            scale = maxScale;
                        }
                    }
                } else {
                    if (scale > 1) {
                        scale -= scaleStep;
                        if (scale < 1) {
                            scale = 1;
                        }
                    }
                }

                const transformValue = `scale(${scale})`;
                image.style.transform = transformValue;

                event.preventDefault();
            }, {
                passive: false
            });
        }
        
    </script>

</body>

</html>