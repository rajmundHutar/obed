<?php

class MenickaCz extends LunchMenuSource {

	public function __construct($title, $link, $icon) {
		$this->title = $title;
		$this->link = $link;
		$this->icon = $icon;
	}

	public function getTodaysMenu($todayDate, $cacheSourceExpires) {

		$cached = $this->downloadHtml($cacheSourceExpires);

		if (!$cached['html']) {
			throw new ScrapingFailedException("No html returned");
		}

		try {
			$result = new LunchMenuResult($cached['stored']);

			$todayBlock = $cached['html']->find("div.obsah div.menicka", 0);

			if (!$todayBlock) {
				throw new ScrapingFailedException("ul.fmenu li.item was not found");
			}

			foreach ($todayBlock->find("ul li.polevka") as $soup) {
				$result->dishes[] = new Dish($this->toUtf($soup));
			}

			foreach ($todayBlock->find("ul li.jidlo") as $item) {
				$dish = $item->find('div.polozka', 0)->plaintext;
				$price = $item->find('div.cena', 0)->plaintext;
				$result->dishes[] = new Dish($this->toUtf($dish), $this->toUtf($price));
			}

		} catch (Exception $e) {
			throw new ScrapingFailedException($e);
		}

		return $result;

	}

	private function toUtf($text) {
		return iconv('CP1250', 'utf-8', trim($text));
	}
}
