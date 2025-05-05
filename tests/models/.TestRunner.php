<?php
class TestRunner {
    private $availableTests = [];
    private $testResults = [
        'passed' => 0,
        'failed' => 0
    ];
    private $resultsBuffer = '';

    public function registerTest($name, $callback, $params = []) {
        $this->availableTests[$name] = [
            'callback' => $callback,
            'params' => $params
        ];
    }
    
    public function runTest($testName) {
        if (!isset($this->availableTests[$testName])) {
            $this->resultsBuffer .= "<div class='alert alert-warning'>Test '$testName' not found.</div>";
            return false;
        }
        
        $test = $this->availableTests[$testName];
        $this->resultsBuffer .= "<h3>Running test: $testName</h3>";
        
        ob_start();
        $result = call_user_func_array($test['callback'], $test['params']);
        $output = ob_get_clean();
        
        $this->resultsBuffer .= "<div class='test-output'>" . $output . "</div>";
        
        if ($result) {
            $this->resultsBuffer .= "<div class='alert alert-success'>Test $testName: PASSED</div>";
            $this->testResults['passed']++;
        } else {
            $this->resultsBuffer .= "<div class='alert alert-danger'>Test $testName: FAILED</div>";
            $this->testResults['failed']++;
        }
        
        return $result;
    }
    
    public function runTests($testNames = []) {
        // Resetear resultados y buffer antes de ejecutar pruebas
        $this->resultsBuffer = '';
        $this->testResults = [
            'passed' => 0,
            'failed' => 0
        ];
        
        if (empty($testNames)) {
            $testNames = array_keys($this->availableTests);
        }
        
        // Mostrar resumen al principio (vacío, se actualizará después)
        echo $this->getSummary();
        
        foreach ($testNames as $testName) {
            if (isset($this->availableTests[$testName])) {
                $this->runTest($testName);
            } else {
                $this->resultsBuffer .= "<div class='alert alert-warning'>Test '$testName' not found.</div>";
            }
        }
        
        // Actualizar el resumen con los resultados
        echo "<script>
            document.getElementById('summary-passed').innerText = '" . $this->testResults['passed'] . "';
            document.getElementById('summary-failed').innerText = '" . $this->testResults['failed'] . "';
            document.getElementById('summary-total').innerText = '" . array_sum($this->testResults) . "';
            
            // Actualizar clase del resumen basado en si hay fallos
            var summaryEl = document.getElementById('test-summary');
            if (" . $this->testResults['failed'] . " > 0) {
                summaryEl.className = 'summary summary-failed';
            } else {
                summaryEl.className = 'summary summary-passed';
            }
        </script>";
        
        // Mostrar los resultados de las pruebas
        echo $this->resultsBuffer;
    }
    
    public function getSummary() {
        return "<div id='test-summary' class='summary sticky-summary'>
            <h3>Test Summary:</h3>
            <ul>
                <li><strong>Passed:</strong> <span id='summary-passed'>" . $this->testResults['passed'] . "</span></li>
                <li><strong>Failed:</strong> <span id='summary-failed'>" . $this->testResults['failed'] . "</span></li>
                <li><strong>Total:</strong> <span id='summary-total'>" . array_sum($this->testResults) . "</span></li>
            </ul>
        </div>";
    }
    
    public function printAvailableTests() {
        echo "<h2>Available tests:</h2>";
        echo "<ul class='test-list'>";
        foreach (array_keys($this->availableTests) as $testName) {
            $suitePart = isset($_GET['suite']) ? "&suite=" . $_GET['suite'] : "";
            echo "<li><a href='?test=$testName$suitePart'>$testName</a></li>";
        }
        echo "</ul>";
    }
    
    public function getAvailableTestsData() {
        return array_keys($this->availableTests);
    }
    
    public function getWebHeader() {
        $titleSuffix = "";
        $navBar = "";
        
        // Verificar si hay un parámetro suite para personalizar el encabezado
        if (isset($_GET['suite'])) {
            $titleSuffix = " - " . $_GET['suite'];
            $navBar = <<<HTML
                <div class="nav">
                    <a href="?">Home</a>
                    <a href="?action=list&suite={$_GET['suite']}">List All Tests</a>
                    <a href="?action=runAll&suite={$_GET['suite']}">Run All Tests</a>
                </div>
            HTML;
        }
        
        return <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <title>PHP Test Runner$titleSuffix</title>
                <link rel="stylesheet" href=".test.css">
            </head>
            <body>
                <div class="container">
                    <h1>PHP Test Runner$titleSuffix</h1>
                    $navBar
        HTML;
    }
    
    public function getWebFooter() {
        return '
            </div>
        </body>
        </html>';
    }
    
    public function handleWebRequest() {
        echo $this->getWebHeader();
        
        $action = $_GET['action'] ?? '';
        $testName = $_GET['test'] ?? '';
        
        if (!empty($testName)) {
            // Ejecutar una prueba específica
            $this->runTests([$testName]);
        } else if ($action === 'list') {
            // Listar todas las pruebas
            $this->printAvailableTests();
        } else if ($action === 'runAll') {
            // Ejecutar todas las pruebas
            $this->runTests();
        } else {
            // Mostrar página principal
            echo "<h2>Welcome to PHP Test Runner</h2>";
            echo "<p>Please select an action from the navigation bar above.</p>";
            
            // Mostrar algunas estadísticas
            echo "<div class='summary'>";
            echo "<h3>Test Suite Information:</h3>";
            echo "<p>Total available tests: " . count($this->availableTests) . "</p>";
            echo "</div>";
            
            // Mostrar lista de tests como alternativa
            $this->printAvailableTests();
        }
        
        echo $this->getWebFooter();
    }
}