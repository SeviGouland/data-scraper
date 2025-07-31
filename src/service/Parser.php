<?php
namespace Service;

require_once __DIR__ . '/../../libraries/simple_html_dom.php';

class Parser
{
    // parses html content and extracts title, price and availability
    public function parse(string $html): array
    {
        $dom = str_get_html($html);
        // if dom parsing fails, return empty fields
        if (!$dom) {
            return ['title' => '', 'price' => '', 'availability' => ''];
        }

        // find the title, price and availability using css selectors
        $title = $dom->find('div.product_main h1', 0);
        $price = $dom->find('p.price_color', 0);
        $availability = $dom->find('p.availability', 0);

        // clean and return the extracted data
        $data = [
            'title' => $title ? trim($title->plaintext) : '',
            'price' => $price ? trim($price->plaintext) : '',
            'availability' => $availability ? trim(preg_replace('/\s+/', ' ', $availability->plaintext)) : '',
        ];

        $dom->clear();
        unset($dom);

        return $data;
    }
}
