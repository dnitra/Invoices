<?php

namespace App\Actions;

use App\Enums\TaxMode;
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
        $invoiceHeader->addChild('inv:number')->addChild('typ:numberRequested', $invoice->invoice_number);
        $invoiceHeader->addChild('inv:date', $invoice->issue_date);
        $invoiceHeader->addChild('inv:dateTax', $invoice->taxable_supply_date);
        $invoiceHeader->addChild('inv:dateDue', $invoice->due_date);

        $accounting = $invoiceHeader->addChild('inv:accounting');
        $accounting->addChild('typ:accountingType', 'withoutAccounting');

        $invoiceHeader->addChild('inv:text', $invoice->invoice_number);

        $partnerIdentity = $invoiceHeader->addChild('inv:partnerIdentity');
        $address = $partnerIdentity->addChild('typ:address');
        $address->addChild('typ:company', $invoice->customer->name);
        $address->addChild('typ:city', $invoice->customer->city);
        $address->addChild('typ:street', $invoice->customer->street);
        $address->addChild('typ:zip', $invoice->customer->zip);
        $address->addChild('typ:ico');
        $address->addChild('typ:dic', $invoice->customer->vat_id);
        $country = $address->addChild('typ:country');
        $country->addChild('typ:ids', $invoice->customer->country);
        $address->addChild('typ:phone', $invoice->customer->phone);
        $address->addChild('typ:email', $invoice->customer->email);

        $paymentType = $invoiceHeader->addChild('inv:paymentType');
        $paymentType->addChild('typ:ids', 'Převodem');
        $paymentType->addChild('typ:paymentType', 'draft');

        $account = $invoiceHeader->addChild('inv:account');
        $account->addChild('typ:accountNo', $invoice->customer->bank_account);
        $account->addChild('typ:bankCode', $invoice->customer->bank_code);

        if ($invoice->tax_mode === TaxMode::OSS->value) {
            $moss = $invoiceHeader->addChild('inv:MOSS');
            $moss->addChild('typ:ids', $invoice->oss);
            $classificationVAT = $invoiceHeader->addChild('inv:classificationVAT');
            $classificationVAT->addChild('typ:ids', 'RDzasEU');
        }

        $invoiceDetail = $invoiceElement->addChild('inv:invoiceDetail');
        foreach ($invoice->rows as $row) {
            $invoiceItem = $invoiceDetail->addChild('inv:invoiceItem');
            $invoiceItem->addChild('inv:text', $row->text);
            $invoiceItem->addChild('inv:quantity', $row->quantity);
            $invoiceItem->addChild('inv:unitPrice', $row->unit_price);
            $invoiceItem->addChild('inv:vatRate', $row->vat_rate);

            if ($invoice->tax_mode === TaxMode::OSS->value) {
                $typeServiceMoss = $invoiceItem->addChild('inv:typeServiceMOSS');
                $typeServiceMoss->addChild('typ:ids', 'SB');
            }
        }

        $invoiceSummary = $invoiceElement->addChild('inv:invoiceSummary');
        $invoiceSummary->addChild('inv:roundingDocument', 'math2one');
        $invoiceSummary->addChild('inv:roundingVAT', 'none');
        $invoiceSummary->addChild('inv:typeCalculateVATInclusivePrice', 'VATNewMethod');

        $roundingVAT = $invoiceSummary->addChild('inv:round');
        $roundingVAT->addChild('typ:priceRound', 0);

        // Convert SimpleXMLElement to DOMDocument for formatting
        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        // Save formatted XML to a string
        $formattedXmlString = $dom->saveXML();

        // Save the formatted XML to a file
        $filename = "invoice_{$invoice->invoice_number}.xml";
        Storage::put($filename, $formattedXmlString);

        return $filename;
    }
}
