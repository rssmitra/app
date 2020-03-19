<?php
/**
 * This is a demo script for the functions of the PHP ESC/POS print driver,
 * Escpos.php.
 *
 * Most printers implement only a subset of the functionality of the driver, so
 * will not render this output correctly in all cases.
 *
 * @author Michael Billington <michael.billington@gmail.com>
 */
require __DIR__ . '/../autoload.php';
use Mike42\Escpos\Printer;
//use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;

/*$connector = new FilePrintConnector("php://stdout");*/
$connector = new WindowsPrintConnector("smb://10.10.10.3/POS-58-Series");
$printer = new Printer($connector);

/* Initialize */
//$printer -> initialize();

/* Text */

$printer -> setJustification(Printer::JUSTIFY_CENTER);

$printer -> setTextSize(2, 2);
$printer -> text("RS. Setia Mitra\n");

$printer -> setTextSize(1, 1);
$printer -> text("Jl. Fatmawati\n");





// $printer -> text("TRANSAKSI\n");
// $printer -> text("\n");

// $printer -> text("12021900057\n");
// $printer -> text("12/02/2019\n");

// $printer -> feed(7);

// /* Text of various (in-proportion) sizes */
// title($printer, "Change height & width\n");
// for ($i = 1; $i <= 8; $i++) {
//     $printer -> setTextSize($i, $i);
//     $printer -> text($i);
// }
// $printer -> text("\n");

// /* Width changing only */
// title($printer, "Change width only (height=4):\n");
// for ($i = 1; $i <= 8; $i++) {
//     $printer -> setTextSize($i, 4);
//     $printer -> text($i);
// }
// $printer -> text("\n");

// /* Height changing only */
// title($printer, "Change height only (width=4):\n");
// for ($i = 1; $i <= 8; $i++) {
//     $printer -> setTextSize(4, $i);
//     $printer -> text($i);
// }
// $printer -> text("\n");

// /* Very narrow text */
// title($printer, "Very narrow text:\n");
// $printer -> setTextSize(1, 8);
// $printer -> text("The quick brown fox jumps over the lazy dog.\n");

// /* Very flat text */
// title($printer, "Very wide text:\n");
// $printer -> setTextSize(4, 1);
// $printer -> text("Hello world!\n");

// /* Very large text */
// title($printer, "Largest possible text:\n");
// $printer -> setTextSize(8, 8);
// $printer -> text("Hello\nworld!\n");

/* Pulse */
$printer -> pulse();

/* Always close the printer! On some PrintConnectors, no actual
 * data is sent until the printer is closed. */
$printer -> close();

function title(Printer $printer, $text)
{
    $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
    $printer -> text("\n" . $text);
    $printer -> selectPrintMode(); // Reset
}

