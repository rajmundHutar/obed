<?php

class Viva extends LunchMenuSource {

	public $title = 'Viva';
	public $link = 'https://pizzerie-viva.cz/';
	public $icon = 'pizza';

	public function getTodaysMenu($todayDate, $cacheSourceExpires) {

		$cached = $this->downloadHtml($cacheSourceExpires);

		if (!$cached['html']) {
			throw new ScrapingFailedException("No html returned");
		}

		try {
			$result = new LunchMenuResult($cached['stored']);

			$todayBlock = $cached['html']->find("div.menus-carousel div.item", 0);

			if (!$todayBlock) {
				throw new ScrapingFailedException("ul.fmenu li.item was not found");
			}

			foreach ($todayBlock->find("ul.list-group li") as $i => $item) {
				if ($i == 0) {
					// Soup
					$result->dishes[] = new Dish($item->plaintext);
				} else {
					// Dish
					$dish = $item->find('div.menu-item', 0)->plaintext;
					$price = $item->find('div.menu-item-price', 0)->plaintext;
					$result->dishes[] = new Dish($dish, $price);
				}


			}

		} catch (Exception $e) {
			throw new ScrapingFailedException($e);
		}

		return $result;

	}

}
