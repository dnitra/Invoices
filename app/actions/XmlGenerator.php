<?php

namespace App\actions;

use Illuminate\Support\Facades\Storage;

class XmlGenerator
{
    public static function generateInvoiceXml($invoice)
    {
        $xmlString = '<?xml version="1.0" encoding="UTF-8"?>';
        $xmlString .= '<dat:dataPack xmlns:dat="http://www.stormware.cz/schema/version_2/data.xsd" xmlns:inv="http://www.stormware.cz/schema/version_2/invoice.xsd" xmlns:typ="http://www.stormware.cz/schema/version_2/type.xsd" id="' . $invoice->id . '" ico="12345678" application="StwTest" version="2.0" note="Import FA">';
        $xmlString .= '<dat:dataPackItem id="' . $invoice->id . '" version="2.0">';
        $xmlString .= '<inv:invoice version="2.0">';
        $xmlString .= '<inv:invoiceHeader>';
        $xmlString .= '<inv:invoiceType>issuedInvoice</inv:invoiceType>';
        $xmlString .= '<inv:date>' . $invoice->issue_date . '</inv:date>';
        $xmlString .= '<inv:dateTax>' . $invoice->taxable_supply_date . '</inv:dateTax>';
        $xmlString .= '<inv:dateDue>' . $invoice->due_date . '</inv:dateDue>';
        $xmlString .= '<inv:accounting>';
        $xmlString .= '<typ:ids>' . $invoice->currency . '</typ:ids>';
        $xmlString .= '</inv:accounting>';
        $xmlString .= '<inv:classificationVAT>';
        $xmlString .= '<typ:classificationVATType>' . ($invoice->vat_rate > 0 ? 'inland' : 'none') . '</typ:classificationVATType>';
        $xmlString .= '</inv:classificationVAT>';
        $xmlString .= '<inv:text>' . $invoice->invoice_number . '</inv:text>';
        $xmlString .= '<inv:paymentType>';
        $xmlString .= '<typ:paymentType>' . ($invoice->status === 'draft' ? 'draft' : 'receipt') . '</typ:paymentType>';
        $xmlString .= '</inv:paymentType>';
        $xmlString .= '<inv:account>';
        $xmlString .= '<typ:ids>' . $invoice->currency . '</typ:ids>';
        $xmlString .= '</inv:account>';
        $xmlString .= '<inv:note>Import XML.</inv:note>';
        $xmlString .= '<inv:intNote>Tento doklad byl vytvořen importem přes XML.</inv:intNote>';
        $xmlString .= '</inv:invoiceHeader>';
        $xmlString .= '<inv:invoiceDetail>';
        foreach ($invoice->rows as $row) {
            $xmlString .= '<inv:invoiceItem>';
            $xmlString .= '<inv:text>' . $row->text . '</inv:text>';
            $xmlString .= '<inv:quantity>' . $row->quantity . '</inv:quantity>';
            $xmlString .= '<inv:unitPrice>' . $row->unit_price . '</inv:unitPrice>';
            $xmlString .= '<inv:vatRate>' . $row->vat_rate . '</inv:vatRate>';
            $xmlString .= '</inv:invoiceItem>';
        }
        $xmlString .= '</inv:invoiceDetail>';
        $xmlString .= '<inv:invoiceSummary>';
        $xmlString .= '<inv:roundingDocument>math2one</inv:roundingDocument>';
        $xmlString .= '<inv:homeCurrency>';
        $xmlString .= '<typ:priceNone>3018</typ:priceNone>';
        $xmlString .= '<typ:priceLow>60000</typ:priceLow>';
        $xmlString .= '<typ:priceHighSum>557</typ:priceHighSum>';
        $xmlString .= '<typ:round>';
        $xmlString .= '<typ:priceRound>0</typ:priceRound>';
        $xmlString .= '</typ:round>';
        $xmlString .= '</inv:homeCurrency>';
        $xmlString .= '</inv:invoiceSummary>';
        $xmlString .= '</inv:invoice>';
        $xmlString .= '</dat:dataPackItem>';
        $xmlString .= '</dat:dataPack>';

        $filename = 'invoice_' . $invoice->id . '.xml';
        Storage::put($filename, $xmlString);

        return $filename;
    }
}
