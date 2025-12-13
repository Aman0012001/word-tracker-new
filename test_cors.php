<?php
// Test CORS and Login endpoint
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>

<head>
    <title>CORS & Login Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        .test-section {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #4CAF50;
        }

        .error {
            border-left-color: #f44336;
        }

        .success {
            color: #4CAF50;
        }

        .fail {
            color: #f44336;
        }

        button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #45a049;
        }

        pre {
            background: #eee;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🔧 Word Tracker Backend Test</h1>

        <div class="test-section">
            <h3>1. Backend Root Test</h3>
            <button onclick="testRoot()">Test Root Endpoint</button>
            <pre id="root-result">Click button to test...</pre>
        </div>

        <div class="test-section">
            <h3>2. Login Endpoint Test (CORS)</h3>
            <button onclick="testLogin()">Test Login from Frontend</button>
            <pre id="login-result">Click button to test...</pre>
        </div>

        <div class="test-section">
            <h3>3. Database Connection</h3>
            <p>
                <?php
                try {
                    require_once 'backend-php/config/database.php';
                    $db = new Database();
                    $conn = $db->getConnection();
                    echo '<span class="success">✅ Database Connected Successfully!</span>';
                } catch (Exception $e) {
                    echo '<span class="fail">❌ Database Error: ' . htmlspecialchars($e->getMessage()) . '</span>';
                }
                ?>
            </p>
        </div>

        <div class="test-section">
            <h3>4. PHP Info</h3>
            <p>PHP Version: <strong><?php echo phpversion(); ?></strong></p>
            <p>Server: <strong><?php echo $_SERVER['SERVER_SOFTWARE']; ?></strong></p>
        </div>
    </div>

    <script>
        async function testRoot() {
            const result = document.getElementById('root-result');
            result.textContent = 'Testing...';

            try {
                const response = await fetch('http://localhost/word-tracker/', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();
                result.textContent = '✅ Success!\n' + JSON.stringify(data, null, 2);
                result.style.color = '#4CAF50';
            } catch (error) {
                result.textContent = '❌ Error: ' + error.message;
                result.style.color = '#f44336';
            }
        }

        async function testLogin() {
            const result = document.getElementById('login-result');
            result.textContent = 'Testing login endpoint from simulated Angular request...';

            try {
                const response = await fetch('http://localhost/word-tracker/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        email: 'test@example.com',
                        password: 'wrongpassword'
                    })
                });

                const data = await response.json();

                if (response.status === 401 || response.status === 400) {
                    result.textContent = '✅ CORS Working! Endpoint accessible.\n' +
                        'Status: ' + response.status + '\n' +
                        'Response: ' + JSON.stringify(data, null, 2) + '\n\n' +
                        'Note: 401/400 is expected for invalid credentials.\n' +
                        'The important thing is NO CORS error!';
                    result.style.color = '#4CAF50';
                } else {
                    result.textContent = 'Response:\n' + JSON.stringify(data, null, 2);
                    result.style.color = '#333';
                }
            } catch (error) {
                result.textContent = '❌ CORS Error or Network Issue:\n' + error.message + '\n\n' +
                    'This means Angular will also fail to connect.';
                result.style.color = '#f44336';
            }
        }
    </script>
</body>

</html>