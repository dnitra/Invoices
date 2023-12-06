<?php

namespace App\actions;

use Illuminate\Support\Facades\Storage;

class XmlGenerator
{
    public static function generateInvoiceXml($invoice)
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><dat:dataPack xmlns:dat="http://www.stormware.cz/schema/version_2/data.xsd" xmlns:inv="http://www.stormware.cz/schema/version_2/invoice.xsd" xmlns:typ="http://www.stormware.cz/schema/version_2/type.xsd"></dat:dataPack>');

        $dataPackItem = $xml->addChild('dat:dataPackItem');
        $dataPackItem->addAttribute('id', $invoice->id);
        $dataPackItem->addAttribute('version', '2.0');

        $invoiceElement = $dataPackItem->addChild('inv:invoice');
        $invoiceElement->addAttribute('version', '2.0');

        $invoiceHeader = $invoiceElement->addChild('inv:invoiceHeader');
        $invoiceHeader->addChild('inv:invoiceType', 'issuedInvoice');
        $invoiceHeader->addChild('inv:date', $invoice->issue_date);
        $invoiceHeader->addChild('inv:dateTax', $invoice->taxable_supply_date);
        $invoiceHeader->addChild('inv:dateDue', $invoice->due_date);

        $accounting = $invoiceHeader->addChild('inv:accounting');
        $accounting->addChild('typ:ids', $invoice->currency);

        $classificationVAT = $invoiceHeader->addChild('inv:classificationVAT');
        $classificationVAT->addChild('typ:classificationVATType', ($invoice->vat_rate > 0 ? 'inland' : 'none'));

        $invoiceHeader->addChild('inv:text', $invoice->invoice_number);

        $paymentType = $invoiceHeader->addChild('inv:paymentType');
        $paymentType->addChild('typ:paymentType', ($invoice->status === 'draft' ? 'draft' : 'receipt'));

        $account = $invoiceHeader->addChild('inv:account');
        $account->addChild('typ:ids', $invoice->currency);

        $invoiceHeader->addChild('inv:note', 'Import XML.');
        $invoiceHeader->addChild('inv:intNote', 'Tento doklad byl vytvořen importem přes XML.');

        $invoiceDetail = $invoiceElement->addChild('inv:invoiceDetail');
        foreach ($invoice->rows as $row) {
            $invoiceItem = $invoiceDetail->addChild('inv:invoiceItem');
            $invoiceItem->addChild('inv:text', $row->text);
            $invoiceItem->addChild('inv:quantity', $row->quantity);
            $invoiceItem->addChild('inv:unitPrice', $row->unit_price);
            $invoiceItem->addChild('inv:vatRate', $row->vat_rate);
        }

        $filename = 'invoice_' . $invoice->id . '.xml';
        Storage::put($filename, $xml->asXML());

        return $filename;
    }
}
