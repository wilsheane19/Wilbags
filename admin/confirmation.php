<?php
require('fpdf.php'); // Include the FPDF library

// Fetch data from the database and generate the report

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('logo.png',10,6,30);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30,10,'Report Title',1,0,'C');
        // Line break
        $this->Ln(20);
    }

    // Load data
    function LoadData($conn)
    {
        // Fetch data from the database and return it as an array
        $data = array();
        // Example query
        $result = $conn->query("SELECT * FROM student_scheduling");
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    // Table with data
    function ImprovedTable($header, $data)
    {
        // Column widths
        $w = array(40, 35, 40, 45);
        // Header
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,$header[$i],1,0,'C');
        $this->Ln();
        // Data
        foreach($data as $row)
        {
            $this->Cell($w[0],6,$row[0],'LR');
            $this->Cell($w[1],6,$row[1],'LR');
            $this->Cell($w[2],6,$row[2],'LR');
            $this->Cell($w[3],6,$row[3],'LR');
            $this->Ln();
        }
        // Closing line
        $this->Cell(array_sum($w),0,'','T');
    }
}

// Create PDF object
$pdf = new PDF();

// Column headings
$header = array('Column 1', 'Column 2', 'Column 3', 'Column 4');

// Connect to your database
$servername = "localhost";
$username = "root";
$password = "";
$database = "login";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Load data
$data = $pdf->LoadData($conn);

// Close the database connection
$conn->close();

// Print PDF
$pdf->SetFont('Arial','',12);
$pdf->AddPage();
$pdf->ImprovedTable($header,$data);
$pdf->Output();
?>
