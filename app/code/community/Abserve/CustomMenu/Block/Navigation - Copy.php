<?php
/**
 * @category    Abserve
 * @package     Abserve_Module
 * @author      Abserve Developer
 * @license     http://abservetech.com/license-agreement/
 * @copyright   Copyright (c) 2015 abservetech (http://abservetech.com)
 */
?>
<?php

class Abserve_CustomMenu_Block_Navigation extends Mage_Catalog_Block_Navigation
{
    const CUSTOM_BLOCK_TEMPLATE = "abserve_custom_menu_%d";

    private $_productsCount     = null;
    private $_topMenu           = array();
    private $_popupMenu         = array();

    public function drawCustomMenuMobileItem($category, $level = 0, $last = false)
    {
        if (!$category->getIsActive()) return '';
        $html = array();
        $id = $category->getId();
        // --- Sub Categories ---
        $activeChildren = $this->_getActiveChildren($category, $level);
        // --- class for active category ---
        $active = ''; if ($this->isCategoryActive($category)) $active = ' act';
        $hasSubMenu = count($activeChildren);
        $catUrl = $this->getCategoryUrl($category);
        $html[] = '<div id="menu-mobile-' . $id . '" class="menu-mobile level0' . $active . '">';
        $html[] = '<div class="parentMenu">';
        // --- Top Menu Item ---
        $html[] = '<a class="category_name  cat_id_'.$id.' level' . $level . $active . '" href="' . $catUrl .'">';
        $name = $this->escapeHtml($category->getName());
        if (Mage::getStoreConfig('custom_menu/general/non_breaking_space')) {
            $name = str_replace(' ', '&nbsp;', $name);
        }
        $html[] = '<span>' . $name . '</span>';
        $html[] = '</a>';
        if ($hasSubMenu) {
            $html[] = '<span class="button" rel="submenu-mobile-' . $id . '" onclick="abserveSubMenuToggle(this, \'menu-mobile-' . $id . '\', \'submenu-mobile-' . $id . '\');">&nbsp;</span>';
        }
        $html[] = '</div>';
        // --- Add Popup block (hidden) ---
        if ($hasSubMenu) {
            // --- draw Sub Categories ---
            $html[] = '<div id="submenu-mobile-' . $id . '" rel="level' . $level . '" class="abserve-custom-menu-submenu" style="display: none;">';
            $html[] = $this->drawMobileMenuItem($activeChildren);
            $html[] = '<div class="clearBoth"></div>';
            $html[] = '</div>';
        }
        $html[] = '</div>';
        $html = implode("\n", $html);
        return $html;
    }

    public function getTopMenuArray()
    {
        return $this->_topMenu;
    }

    public function getPopupMenuArray()
    {
        return $this->_popupMenu;
    }

    public function drawCustomMenuItem($category, $level = 0, $last = false)
    {
        if (!$category->getIsActive()) return;
        $htmlTop = array();
        $id = $category->getId();
        // --- Static Block ---
        $blockId = sprintf(self::CUSTOM_BLOCK_TEMPLATE, $id); // --- static block key
        #Mage::log($blockId);
        $collection = Mage::getModel('cms/block')->getCollection()
            ->addFieldToFilter('identifier', array(array('like' => $blockId . '_w%'), array('eq' => $blockId)))
            ->addFieldToFilter('is_active', 1);
        $blockId = $collection->getFirstItem()->getIdentifier();
        #Mage::log($blockId);
        $blockHtml = Mage::app()->getLayout()->createBlock('cms/block')->setBlockId($blockId)->toHtml();
        // --- Sub Categories ---
        $activeChildren = $this->_getActiveChildren($category, $level);
        // --- class for active category ---
        $active = ''; if ($this->isCategoryActive($category)) $active = ' act';
        // --- Popup functions for show ---
        $drawPopup = ($blockHtml || count($activeChildren));
        if ($drawPopup) {
            $htmlTop[] = '<div id="menu' . $id . '" class="menu' . $active . '" onmouseover="abserveShowMenuPopup(this, event, \'popup' . $id . '\',\''.$id.'\');" onmouseout="abserveHideMenuPopup(this, event, \'popup' . $id . '\', \'menu' . $id . '\', \''.$id.'\')">';
        } else {
            $htmlTop[] = '<div id="menu' . $id . '" class="menu' . $active . '">';
        }


        // --- Top Menu Item ---
        $htmlTop[] = '<div class="parentMenu">';
        if ($level == 0 && $drawPopup) {
            $htmlTop[] = '<a  class="category_name  cat_id_'.$id.' level' . $level . $active . '" href="'.$this->getCategoryUrl($category).'" rel="'.$this->getCategoryUrl($category).'">';
        } else {
            $htmlTop[] = '<a  class="category_name  cat_id_'.$id.' level' . $level . $active . '" href="'.$this->getCategoryUrl($category).'">';
        }

        $name = $this->escapeHtml($category->getName());
        if (Mage::getStoreConfig('custom_menu/general/non_breaking_space')) {
            $name = str_replace(' ', '&nbsp;', $name);
        }
        $htmlTop[] = '<span>' . $name . '</span>';
        $htmlTop[] = '</a>';
        $htmlTop[] = '</div>';
        $htmlTop[] = '</div>';
        $this->_topMenu[] = implode("\n", $htmlTop);
        // --- Add Popup block (hidden) ---
        if ($drawPopup) {
            $htmlPopup = array();
            // --- Popup function for hide ---
            $htmlPopup[] = '<div id="popup' . $id . '" class="abserve-custom-menu-popup" onmouseout="abserveHideMenuPopup(this, event, \'popup' . $id . '\', \'menu' . $id . '\')" onmouseover="abservePopupOver(this, event, \'popup' . $id . '\', \'menu' . $id . '\')">';
            // --- draw Sub Categories ---
            if (count($activeChildren)) {
                $columns = (int)Mage::getStoreConfig('custom_menu/columns/count');
                $htmlPopup[] = '<div class="block1">';                
                $htmlPopup[] = $this->drawColumns($activeChildren, $columns,$id);
                $htmlPopup[] = $this->drawColumnsImage($activeChildren, $columns,$id);
                $htmlPopup[] = '<div class="clearBoth"></div>';
                $htmlPopup[] = '</div>';
            }


            // -- Shown Product List -- 
            $htmlPopup[] = '<div id="' . $blockId . '" class="block2 product_img_block">';

            // Get Product List :
			$category = new Mage_Catalog_Model_Category();
			$category->load($id);
			$collection = $category->getProductCollection();      
			$collection->addAttributeToSelect('*');      
			foreach ($collection as $_product) {
				$image_url= Mage::getModel('catalog/product_media_config')->getMediaUrl( $_product->getImage() );
			    $product_url=  Mage::getUrl($_product->getUrlPath());
			}

			/*$htmlPopup[] = '<div class="row">';
			$htmlPopup[] = '<div id="owl-demo" class="owl-carousel">';
			foreach ($collection as $_product) {
				$image_url= Mage::getModel('catalog/product_media_config')->getMediaUrl( $_product->getImage() );
			    $product_url=  Mage::getUrl($_product->getUrlPath());			    

				$htmlPopup[] = '<div class="slide-block item" style="background:url('.$image_url.');background-size:contain;background-repeat:no-repeat;">';
					$htmlPopup[] = '<div class="text-block">';
						$htmlPopup[] = '<a class="product_title_text" href="'.$product_url.'"> '.$_product->getName().'</a>';
						$htmlPopup[] = '<h5>Flat 40% Offer</h5>';
						$htmlPopup[] = '<a class="product_buy" href="'.$product_url.'">Buy Now</a>';
					$htmlPopup[] = '</div>';
				$htmlPopup[] = '</div>';
			}
			$htmlPopup[] = '</div>';
			$htmlPopup[] = '</div>';*/



            $htmlPopup[] = '</div>';

            // --- draw Custom User Block ---
            if ($blockHtml) {
                $htmlPopup[] = '<div id="' . $blockId . '" class="block2">';
                $htmlPopup[] = $blockHtml;
                $htmlPopup[] = '</div>';
            }
            $htmlPopup[] = '</div>';
            $this->_popupMenu[] = implode("\n", $htmlPopup);
        }
    }

    public function drawMobileMenuItem($children, $level = 1)
    {
        $keyCurrent = $this->getCurrentCategory()->getId();
        $html = '';
        foreach ($children as $child) {
            if (is_object($child) && $child->getIsActive()) {
                // --- class for active category ---
                $active = '';
                $id = $child->getId();
                $activeChildren = $this->_getActiveChildren($child, $level);
                if ($this->isCategoryActive($child)) {
                    $active = ' actParent';
                    if ($id == $keyCurrent) $active = ' act';
                }
                $html.= '<div id="menu-mobile-' . $id . '" class="itemMenu level' . $level . $active . '">';
                // --- format category name ---
                $name = $this->escapeHtml($child->getName());
                if (Mage::getStoreConfig('custom_menu/general/non_breaking_space')) $name = str_replace(' ', '&nbsp;', $name);
                $html.= '<div class="parentMenu">';
                $html.= '<a class="category_name  cat_id_'.$id.' itemMenuName level' . $level . $active . '" href="' . $this->getCategoryUrl($child) . '"><span>' . $name . '</span></a>';
                if (count($activeChildren) > 0) {
                    $html.= '<span class="button" rel="submenu-mobile-' . $id . '" onclick="abserveSubMenuToggle(this, \'menu-mobile-' . $id . '\', \'submenu-mobile-' . $id . '\');">&nbsp;</span>';
                }
                $html.= '</div>';
                if (count($activeChildren) > 0) {
                    $html.= '<div id="submenu-mobile-' . $id . '" rel="level' . $level . '" class="abserve-custom-menu-submenu level' . $level . '" style="display: none;">';
                    $html.= $this->drawMobileMenuItem($activeChildren, $level + 1);
                    $html.= '<div class="clearBoth"></div>';
                    $html.= '</div>';
                }
                $html.= '</div>';
            }
        }
        return $html;
    }

    public function drawMenuImageItem($children, $level = 1,$main_cat_id)
    {
        //$html = '<div class=" cat_id_img_'.$level.'">';
        $keyCurrent = $this->getCurrentCategory()->getId();
        foreach ($children as $child) {
            if (is_object($child) && $child->getIsActive()) {
                // --- class for active category ---
                $active = '';
                if ($this->isCategoryActive($child)) {
                    $active = ' actParent';
                    if ($child->getId() == $keyCurrent) $active = ' act';
                }
                // --- format category name ---
                $name = $this->escapeHtml($child->getName());
                if (Mage::getStoreConfig('custom_menu/general/non_breaking_space')) $name = str_replace(' ', '&nbsp;', $name);
                
                $activeChildren = $this->_getActiveChildren($child, $level);
                if (count($activeChildren) > 0) {
                    $html.= '<div class="itemSubMenu level' . $level . '">';
                    $html.= $this->drawMenuImageItem($activeChildren, $level + 1);
                    $html.= '</div>';
                }

            }


            $catimagename = Mage::getModel('catalog/category')->load($child->getId())->getThumbnail();
            $catimgsrc = Mage::getBaseUrl('media').'catalog/category/'.$catimagename;
            if($catimagename)
            // $catimage = '<span style="background:url('.$catimgsrc.');" class="category_image img_cat_id_'.$child->getId().'" > <img  style="height:300px;float:right;" src="'.$catimgsrc.'" /> </span>';
                $catimage = '<div> <span style="background:url('.$catimgsrc.'); height: 232px;background-size: contain !important; background-repeat: no-repeat; background-position: center center; margin: 0 auto;" class="category_image img_cat_id_'.$child->getId().'" >  </span></div>';
            else{
                $catimgurl = $this->getSkinUrl('css/abservemegamenu/images/cat_img.png');
                $catimage = '<div> <span style="background:url('.$catimgurl.'); height: 232px;background-size: contain !important; background-repeat: no-repeat; background-position: center center; margin: 0 auto;" class="category_image img_cat_id_'.$child->getId().'" >  </span></div>';
            }
            //$catimage = '';
            $html.=$catimage;


        }

        //$html.= '</div>';

        return $html;
    }

    public function drawMenuItem($children, $level = 1,$main_cat_id)
    {
        $html = '<div class="itemMenu level' . $level . ' main_cat_id'.$main_cat_id.'">';
        $keyCurrent = $this->getCurrentCategory()->getId();
        foreach ($children as $child) {
            if (is_object($child) && $child->getIsActive()) {
                // --- class for active category ---
                $active = '';
                if ($this->isCategoryActive($child)) {
                    $active = ' actParent';
                    if ($child->getId() == $keyCurrent) $active = ' act';
                }
                // --- format category name ---
                $name = $this->escapeHtml($child->getName());
                if (Mage::getStoreConfig('custom_menu/general/non_breaking_space')) $name = str_replace(' ', '&nbsp;', $name);
                $html.= '<div class="submenu" onmouseover="abserveHoverSubcategory(\''.$child->getId().'\',\''.$main_cat_id.'\');">';
                $html.= '<a class="cat_id_'.$child->getId().' itemMenuName level' . $level . $active . '" href="' . $this->getCategoryUrl($child) . '" ><span>' . $name . '</span></a>';
                $activeChildren = $this->_getActiveChildren($child, $level);
                if (count($activeChildren) > 0) {
                    $html.= '<div class="itemSubMenu level' . $level . '">';
                    $html.= $this->drawSubMenuItem($activeChildren, $level + 1);
                    $html.= '</div>';
                }
                $html .= '</div>';

            }
        }

        $html.= '</div>';

        return $html;
    }


    public function drawSubMenuItem($children, $level = 1,$main_cat_id)
    {
        $html = '<div class="itemMenu level' . $level . ' main_cat_id'.$main_cat_id.'">';
        $keyCurrent = $this->getCurrentCategory()->getId();
        foreach ($children as $child) {
            if (is_object($child) && $child->getIsActive()) {
                // --- class for active category ---
                $active = '';
                if ($this->isCategoryActive($child)) {
                    $active = ' actParent';
                    if ($child->getId() == $keyCurrent) $active = ' act';
                }
                // --- format category name ---
                $name = $this->escapeHtml($child->getName());
                if (Mage::getStoreConfig('custom_menu/general/non_breaking_space')) $name = str_replace(' ', '&nbsp;', $name);
                $html.= '<a class="cat_id_'.$child->getId().' itemMenuName level' . $level . $active . '" href="'.$this->getCategoryUrl($child).'"><span>' . $name . '</span></a>';
                $activeChildren = $this->_getActiveChildren($child, $level);
                if (count($activeChildren) > 0) {
                    $html.= '<div class="itemSubMenu level' . $level . '">';
                    $html.= $this->drawSubMenuItem($activeChildren, $level + 1);
                    $html.= '</div>';
                }

            }
        }

        $html.= '</div>';

        return $html;
    }


    public function drawColumnsImage($children, $columns = 1,$main_cat_id)
    {
        $html = '';
        $html .= '<div class="category_image_wrapper" >';
        // --- explode by columns ---
        if ($columns < 1) $columns = 1;
        $chunks = $this->_explodeByColumns($children, $columns);
        // --- draw columns ---
        $lastColumnNumber = count($chunks);
        $i = 1;
        foreach ($chunks as $key => $value) {
            if (!count($value)) continue;
            //$html.= '<div class="column">';
            $html.= $this->drawMenuImageItem($value, 1,$main_cat_id);
            //$html.= '</div>';
            $i++;
        }

        //-- Main Category Image --
            $catimagename = Mage::getModel('catalog/category')->load($main_cat_id)->getThumbnail();
            $catimgsrc = Mage::getBaseUrl('media').'catalog/category/'.$catimagename;
            if($catimagename){
                //$catimage = '<span style="background:url('.$catimgsrc.');"  class="category_image img_cat_id_'.$main_cat_id.'" ><img src="'.$catimgsrc.'" style="height:300px;float:right;"/> </span>';
                $catimage = '<div><span style="background:url('.$catimgsrc.'); height: 232px;background-size: contain !important; background-repeat: no-repeat; background-position: center center; margin: 0 auto;"  class="category_image img_cat_id_'.$main_cat_id.'" ></span></div>';
            }
            else{
                $catimgurl = $this->getSkinUrl('css/abservemegamenu/images/cat_img.png');
                $catimage = '<div><span style="background:url('.$catimgurl.'); height: 232px;background-size: contain !important; background-repeat: no-repeat; background-position: center center; margin: 0 auto;"  class="category_image img_cat_id_'.$main_cat_id.'" ></span></div>';
                //$catimage = '<div><span style="background:url('.$catimgsrc.'); height: 232px;background-size: contain !important; background-repeat: no-repeat; background-position: center center; margin: 0 auto;"  class="category_image img_cat_id_'.$main_cat_id.'" ></span></div>';
            }
        $html .= $catimage;
        $html .= '</div>';
        return $html;
    }

    public function drawColumns($children, $columns = 1,$main_cat_id)
    {
        $html = '';
        // --- explode by columns ---
        if ($columns < 1) $columns = 1;
        $chunks = $this->_explodeByColumns($children, $columns);
        // --- draw columns ---
        $lastColumnNumber = count($chunks);
        $i = 1;
        foreach ($chunks as $key => $value) {
            if (!count($value)) continue;
            $class = '';
            if ($i == 1) $class.= ' first';
            if ($i == $lastColumnNumber) $class.= ' last';
            if ($i % 2) $class.= ' odd'; else $class.= ' even';
            $html.= '<div class="column' . $class . '">';
            $html.= $this->drawMenuItem($value, 1,$main_cat_id);
            $html.= '</div>';
            $i++;
        }
        
        return $html;
    }

    protected function _getActiveChildren($parent, $level)
    {
        $activeChildren = array();
        // --- check level ---
        $maxLevel = (int)Mage::getStoreConfig('custom_menu/general/max_level');
        if ($maxLevel > 0) {
            if ($level >= ($maxLevel - 1)) return $activeChildren;
        }
        // --- / check level ---
        if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $children = $parent->getChildrenNodes();
            $childrenCount = count($children);
        } else {
            $children = $parent->getChildren();
            $childrenCount = $children->count();
        }
        $hasChildren = $children && $childrenCount;
        if ($hasChildren) {
            foreach ($children as $child) {
                if ($this->_isCategoryDisplayed($child)) {
                    array_push($activeChildren, $child);
                }
            }
        }
        return $activeChildren;
    }

    private function _isCategoryDisplayed(&$child)
    {
        if (!$child->getIsActive()) return false;
        // === check products count ===
        // --- get collection info ---
        if (!Mage::getStoreConfig('custom_menu/general/display_empty_categories')) {
            $data = $this->_getProductsCountData();
            // --- check by id ---
            $id = $child->getId();
            #Mage::log($id); Mage::log($data);
            if (!isset($data[$id]) || !$data[$id]['product_count']) return false;
        }
        // === / check products count ===
        return true;
    }

    private function _getProductsCountData()
    {
        if (is_null($this->_productsCount)) {
            $collection = Mage::getModel('catalog/category')->getCollection();
            $storeId = Mage::app()->getStore()->getId();
            /* @var $collection Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection */
            $collection->addAttributeToSelect('name')
                ->addAttributeToSelect('is_active')
                ->setProductStoreId($storeId)
                ->setLoadProductCount(true)
                ->setStoreId($storeId);
            $productsCount = array();
            foreach($collection as $cat) {
                $productsCount[$cat->getId()] = array(
                    'name' => $cat->getName(),
                    'product_count' => $cat->getProductCount(),
                );
            }
            #Mage::log($productsCount);
            $this->_productsCount = $productsCount;
        }
        return $this->_productsCount;
    }

    private function _explodeByColumns($target, $num)
    {
        if ((int)Mage::getStoreConfig('custom_menu/columns/divided_horizontally')) {
            $target = self::_explodeArrayByColumnsHorisontal($target, $num);
        } else {
            $target = self::_explodeArrayByColumnsVertical($target, $num);
        }
        #return $target;
        if ((int)Mage::getStoreConfig('custom_menu/columns/integrate') && count($target)) {
            // --- combine consistently numerically small column ---
            // --- 1. calc length of each column ---
            $max = 0; $columnsLength = array();
            foreach ($target as $key => $child) {
                $count = 0;
                $this->_countChild($child, 1, $count);
                if ($max < $count) $max = $count;
                $columnsLength[$key] = $count;
            }
            // --- 2. merge small columns with next ---
            $xColumns = array(); $column = array(); $cnt = 0;
            $xColumnsLength = array(); $k = 0;
            foreach ($columnsLength as $key => $count) {
                $cnt+= $count;
                if ($cnt > $max && count($column)) {
                    $xColumns[$k] = $column;
                    $xColumnsLength[$k] = $cnt - $count;
                    $k++; $column = array(); $cnt = $count;
                }
                $column = array_merge($column, $target[$key]);
            }
            $xColumns[$k] = $column;
            $xColumnsLength[$k] = $cnt - $count;
            // --- 3. integrate columns of one element ---
            $target = $xColumns; $xColumns = array(); $nextKey = -1;
            if ($max > 1 && count($target) > 1) {
                foreach($target as $key => $column) {
                    if ($key == $nextKey) continue;
                    if ($xColumnsLength[$key] == 1) {
                        // --- merge with next column ---
                        $nextKey = $key + 1;
                        if (isset($target[$nextKey]) && count($target[$nextKey])) {
                            $xColumns[] = array_merge($column, $target[$nextKey]);
                            continue;
                        }
                    }
                    $xColumns[] = $column;
                }
                $target = $xColumns;
            }
        }
        $_rtl = Mage::getStoreConfigFlag('custom_menu/general/rtl');
        if ($_rtl) {
            $target = array_reverse($target);
        }
        return $target;
    }

    private function _countChild($children, $level, &$count)
    {
        foreach ($children as $child) {
            if ($child->getIsActive()) {
                $count++; $activeChildren = $this->_getActiveChildren($child, $level);
                if (count($activeChildren) > 0) $this->_countChild($activeChildren, $level + 1, $count);
            }
        }
    }

    private static function _explodeArrayByColumnsHorisontal($list, $num)
    {
        if ($num <= 0) return array($list);
        $partition = array();
        $partition = array_pad($partition, $num, array());
        $i = 0;
        foreach ($list as $key => $value) {
            $partition[$i][$key] = $value;
            if (++$i == $num) $i = 0;
        }
        return $partition;
    }

    private static function _explodeArrayByColumnsVertical($list, $num)
    {
        if ($num <= 0) return array($list);
        $listlen = count($list);
        $partlen = floor($listlen / $num);
        $partrem = $listlen % $num;
        $partition = array();
        $mark = 0;
        for ($column = 0; $column < $num; $column++) {
            $incr = ($column < $partrem) ? $partlen + 1 : $partlen;
            $partition[$column] = array_slice($list, $mark, $incr);
            $mark += $incr;
        }
        return $partition;
    }
}
