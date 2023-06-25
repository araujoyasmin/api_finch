<?php


// Configura��o do Swagger UI
$swaggerJsonPath = 'swagger.json';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Documenta��o da API</title>
    <link rel="stylesheet" type="text/css" href="dist/swagger-ui.css">
</head>
<body>
    <div id="swagger-ui"></div>

    <script src="dist/swagger-ui-bundle.js"></script>
    <script src="dist/swagger-ui-standalone-preset.js"></script>
    <script>
        // Configura��o do Swagger UI
        window.onload = function() {
            const ui = SwaggerUIBundle({
                url: "<?php echo $swaggerJsonPath; ?>",
                dom_id: '#swagger-ui',
                presets: [
                    SwaggerUIBundle.presets.apis, 
                    SwaggerUIStandalonePreset
                ],
                layout: "BaseLayout"
            });

            window.ui = ui;
        };
    </script>
</body>
</html>