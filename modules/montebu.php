<?php

class MonteBu extends LunchMenuSource {

	public $title = 'Monte Bú';
	public $link = 'http://www.monte-bu.cz/menu.php';
	public $icon = 'montebu';

	public function getTodaysMenu($todayDate, $cacheSourceExpires) {

		$cached = $this->downloadHtml($cacheSourceExpires);

		if (!$cached['html']) {
			throw new ScrapingFailedException("No html returned");
		}
		try {
			$result = new LunchMenuResult($cached['stored']);
			$group = null;

			$section = $cached['html']->find("main.main-box section", 4);

			if (!$section) {
				throw new ScrapingFailedException("div.menu-table was not found");
			}

			$soup = $section->find("li.today p.polevka", 0)->plaintext;

			// Soup
			$result->dishes[] = new Dish($soup);

			foreach ($section->find("li.today p.jidlobase") as $i => $item) {

				$dishName = $item->plaintext;
				$price = $item->find('span', 0)->plaintext;
				$dishName = trim(str_replace($price, '', $dishName));
				// Dish
				$result->dishes[] = new Dish($dishName, $price);

			}
		} catch (Exception $e) {
			throw new ScrapingFailedException($e);
		}

		return $result;

	}

}
