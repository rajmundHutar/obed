<?php

class Sono extends LunchMenuSource {

	public $title = 'Flames grill pub & restaurant';
	public $link = 'http://www.flames-grill.cz/';
	public $icon = 'sono-logo';

	public function getTodaysMenu($todayDate, $cacheSourceExpires) {
		$cached = $this->downloadHtml($cacheSourceExpires);

		if (!$cached['html']) {
			throw new ScrapingFailedException("No html returned");
		}
		try {
			$result = new LunchMenuResult($cached['stored']);

			foreach ($cached['html']->find("ul.foodlist li") as $i => $item) {

				if ($i == 0) {
					// Soup
					$dishName = $item->plaintext;
					if ($dishName) {
						$result->dishes[] = new Dish($dishName);
					}
				} else {
					// Other
					$dishName = $item->plaintext;
					$price = $item->find('span.price', 0)->plaintext;
					$dishName = str_replace($price, '', $dishName);
					if ($dishName) {
						$result->dishes[] = new Dish($dishName, $price);
					}
				}
			}

		} catch (Exception $e) {
			throw new ScrapingFailedException($e);
		}

		return $result;

	}
}
