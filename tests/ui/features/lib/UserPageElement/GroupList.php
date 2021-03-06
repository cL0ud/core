<?php

/**
 * ownCloud
 *
 * @author Artur Neumann <artur@jankaritech.com>
 * @copyright 2017 Artur Neumann artur@jankaritech.com
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License,
 * as published by the Free Software Foundation;
 * either version 3 of the License, or any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace Page\UserPageElement;

use Behat\Mink\Element\NodeElement;
use Page\OwncloudPage;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\ElementNotFoundException;

/**
 * The list of groups
 *
 */
class GroupList extends OwncloudPage {

	/**
	 * @var NodeElement of this element
	 */
	protected $groupListElement;
	protected $allGroupsXpath = "//li[@class='isgroup']";
	protected $groupLiXpath = "//li[@data-gid=%s]";
	protected $deleteBtnXpath = "//a[@class='action delete']";

	/**
	 * sets the NodeElement for the current group list
	 * a little bit like __construct() but as we access this "sub-page-object"
	 * from an other Page Object by $this->getPage("OwncloudPageElement\\GroupList")
	 * there is no real __construct() that can take arguments
	 *
	 * @param \Behat\Mink\Element\NodeElement $groupListElement
	 * @return void
	 */
	public function setElement(NodeElement $groupListElement) {
		$this->groupListElement = $groupListElement;
	}

	/**
	 * 
	 * @param string $name
	 * @throws ElementNotFoundException
	 * @return \Behat\Mink\Element\NodeElement
	 */
	public function selectGroup($name) {
		$name = $this->quotedText($name);
		$xpathLocator = sprintf($this->groupLiXpath, $name);
		$groupLi = $this->groupListElement->find(
			"xpath", $xpathLocator
		);
		if (is_null($groupLi)) {
			throw new ElementNotFoundException(
				__METHOD__ .
				" xpath $xpathLocator " .
				"could not find group list element"
			);
		}
		$groupLi->click();
		return $groupLi;
	}

	/**
	 * deletes a group in the UI
	 * 
	 * @param string $name
	 * @throws ElementNotFoundException
	 * @return void
	 */
	public function deleteGroup($name) {
		$groupLi = $this->selectGroup($name);
		$deleteButton = $groupLi->find("xpath", $this->deleteBtnXpath);
		if (is_null($deleteButton)) {
			throw new ElementNotFoundException(
				__METHOD__ .
				" xpath $this->deleteBtnXpath " .
				"could not find delete button"
			);
		}
		$deleteButton->click();
	}

	/**
	 * returns all group names in an array
	 * 
	 * @return string[]
	 */
	public function namesToArray() {
		$allGroupElements = $this->groupListElement->findAll(
			"xpath", $this->allGroupsXpath
		);
		$allGroups = [];
		foreach ($allGroupElements as $element) {
			$allGroups[] = $this->getTrimmedText($element);
		}
		return $allGroups;
	}
}
	