
/*jQuery(document).ready(function(){

    jQuery("#owl-demo").owlCarousel({
        items : 4,
        lazyLoad : true,
        navigation : true
    });

});*/

var abserveMenuLoaded = false;
var abserveMobileMenuLoaded = false;

function abserveInitPopupContent()
{
    if (abserveMenuLoaded) return;
    var xMenu = $('custommenu');
    if (typeof abservePopupMenuContent != 'undefined') xMenu.innerHTML = abservePopupMenuContent + xMenu.innerHTML;
    abserveMenuLoaded = true;
}

function abserveInitMobileMenuContent()
{
    if (abserveMobileMenuLoaded) return;
    var xMenu = $('menu-content');
    if (typeof abserveMobileMenuContent != 'undefined') xMenu.innerHTML = abserveMobileMenuContent;
    abserveMobileMenuLoaded = true;
}

function abserveShowMenuPopup(objMenu, event, popupId, id)
{
    var id = parseInt(id);
    var img_cat_id = ".img_cat_id_"+id;
    jQuery(".category_image").removeClass("active");
    jQuery(img_cat_id).addClass("active");


    abserveInitPopupContent();
    if (typeof abserveCustommenuTimerHide[popupId] != 'undefined') clearTimeout(abserveCustommenuTimerHide[popupId]);
    objMenu = $(objMenu.id); var popup = $(popupId); if (!popup) return;
    if (!!abserveActiveMenu) {
        abserveHideMenuPopup(objMenu, event, abserveActiveMenu.popupId, abserveActiveMenu.menuId);
    }
    abserveActiveMenu = {menuId: objMenu.id, popupId: popupId};
    if (!objMenu.hasClassName('active')) {
        abserveCustommenuTimerShow[popupId] = setTimeout(function() {
            objMenu.addClassName('active');
            var popupWidth = CUSTOMMENU_POPUP_WIDTH;
            if (!popupWidth) popupWidth = popup.getWidth();
            var pos = abservePopupPos(objMenu, popupWidth);
            popup.style.top = pos.top + 'px';
            popup.style.left = pos.left + 'px';
            abserveSetPopupZIndex(popup);
            if (CUSTOMMENU_POPUP_WIDTH)
                popup.style.width = CUSTOMMENU_POPUP_WIDTH + 'px';
            // --- Static Block width ---
            var block2 = $(popupId).select('div.block2');
            if (typeof block2[0] != 'undefined') {
                var wStart = block2[0].id.indexOf('_w');
                if (wStart > -1) {
                    var w = block2[0].id.substr(wStart+2);
                } else {
                    var w = 0;
                    $(popupId).select('div.block1 div.column').each(function(item) {
                        w += $(item).getWidth();
                    });
                }
                if (w) block2[0].style.width = w + 'px';
            }
            // --- change href ---
            var abserveMenuAnchor = $(objMenu.select('a')[0]);
            abserveChangeTopMenuHref(abserveMenuAnchor, true);
            // --- show popup ---
            if (typeof jQuery == 'undefined') {
                popup.style.display = 'block';
            } else {
                jQuery('#' + popupId).stop(true, true).fadeIn();
            }
        }, CUSTOMMENU_POPUP_DELAY_BEFORE_DISPLAYING);
    }
}

function abserveHideMenuPopup(element, event, popupId, menuId, id)
{

    var id = parseInt(id);
    var img_cat_id = ".img_cat_id_"+id;
    //jQuery(img_cat_id).removeClass("active");

    if (typeof abserveCustommenuTimerShow[popupId] != 'undefined') clearTimeout(abserveCustommenuTimerShow[popupId]);
    var element = $(element); var objMenu = $(menuId) ;var popup = $(popupId); if (!popup) return;
    var abserveCurrentMouseTarget = getCurrentMouseTarget(event);
    if (!!abserveCurrentMouseTarget) {
        if (!abserveIsChildOf(element, abserveCurrentMouseTarget) && element != abserveCurrentMouseTarget) {
            if (!abserveIsChildOf(popup, abserveCurrentMouseTarget) && popup != abserveCurrentMouseTarget) {
                if (objMenu.hasClassName('active')) {
                    abserveCustommenuTimerHide[popupId] = setTimeout(function() {
                        objMenu.removeClassName('active');
                        // --- change href ---
                        var abserveMenuAnchor = $(objMenu.select('a')[0]);
                        abserveChangeTopMenuHref(abserveMenuAnchor, false);

                        //-- Remove class active

                        /*var id = parseInt(id);
                        var img_cat_id = ".img_cat_id_"+id;
                        if(jQuery(img_cat_id).hasClassName('active')){
                            jQuery(img_cat_id).removeClass("active");
                        }*/
                        


                        // --- hide popup ---
                        if (typeof jQuery == 'undefined') {
                            popup.style.display = 'none';

                        } else {
                            jQuery('#' + popupId).stop(true, true).fadeOut();
                        }
                    }, CUSTOMMENU_POPUP_DELAY_BEFORE_HIDING);
                }
            }
        }
    }
}

function abservePopupOver(element, event, popupId, menuId)
{
    if (typeof abserveCustommenuTimerHide[popupId] != 'undefined') clearTimeout(abserveCustommenuTimerHide[popupId]);
}

function abservePopupPos(objMenu, w)
{
    var pos = objMenu.cumulativeOffset();
    var wraper = $('custommenu');
    var posWraper = wraper.cumulativeOffset();
    var xTop = pos.top - posWraper.top
    if (CUSTOMMENU_POPUP_TOP_OFFSET) {
        xTop += CUSTOMMENU_POPUP_TOP_OFFSET;
    } else {
        xTop += objMenu.getHeight();
    }
    var res = {'top': xTop};
    if (CUSTOMMENU_RTL_MODE) {
        var xLeft = pos.left - posWraper.left - w + objMenu.getWidth();
        if (xLeft < 0) xLeft = 0;
        res.left = xLeft;
    } else {
        var wWraper = wraper.getWidth();
        var xLeft = pos.left - posWraper.left;
        if ((xLeft + w) > wWraper) xLeft = wWraper - w;
        if (xLeft < 0) xLeft = 0;
        res.left = xLeft;
    }
    return res;
}

function abserveChangeTopMenuHref(abserveMenuAnchor, state)
{
    if (state) {
        abserveMenuAnchor.href = abserveMenuAnchor.rel;
    } else {
        abserveMenuAnchor.href = 'javascript:void(0);';
    }
}

function abserveIsChildOf(parent, child)
{
    if (child != null) {
        while (child.parentNode) {
            if ((child = child.parentNode) == parent) {
                return true;
            }
        }
    }
    return false;
}

function abserveSetPopupZIndex(popup)
{
    $$('.abserve-custom-menu-popup').each(function(item){
       item.style.zIndex = '9999';
    });
    popup.style.zIndex = '10000';
}

function getCurrentMouseTarget(xEvent)
{
    var abserveCurrentMouseTarget = null;
    if (xEvent.toElement) {
        abserveCurrentMouseTarget = xEvent.toElement;
    } else if (xEvent.relatedTarget) {
        abserveCurrentMouseTarget = xEvent.relatedTarget;
    }
    return abserveCurrentMouseTarget;
}

function getCurrentMouseTargetMobile(xEvent)
{
    if (!xEvent) var xEvent = window.event;
    var abserveCurrentMouseTarget = null;
    if (xEvent.target) abserveCurrentMouseTarget = xEvent.target;
        else if (xEvent.srcElement) abserveCurrentMouseTarget = xEvent.srcElement;
    if (abserveCurrentMouseTarget.nodeType == 3) // defeat Safari bug
        abserveCurrentMouseTarget = abserveCurrentMouseTarget.parentNode;
    return abserveCurrentMouseTarget;
}

/* Mobile */
function abserveMenuButtonToggle()
{
    $('menu-content').toggle();
}

function abserveGetMobileSubMenuLevel(id)
{
    var rel = $(id).readAttribute('rel');
    return parseInt(rel.replace('level', ''));
}

function abserveSubMenuToggle(obj, activeMenuId, activeSubMenuId)
{
    var currLevel = abserveGetMobileSubMenuLevel(activeSubMenuId);
    // --- hide submenus ---
    $$('.abserve-custom-menu-submenu').each(function(item) {
        if (item.id == activeSubMenuId) return;
        var xLevel = abserveGetMobileSubMenuLevel(item.id);
        if (xLevel >= currLevel) {
            $(item).hide();
        }
    });
    // --- reset button state ---
    $('custommenu-mobile').select('span.button').each(function(xItem) {
        var subMenuId = $(xItem).readAttribute('rel');
        if (!$(subMenuId).visible()) {
            $(xItem).removeClassName('open');
        }
    });
    // ---
    if ($(activeSubMenuId).getStyle('display') == 'none') {
        $(activeSubMenuId).show();
        $(obj).addClassName('open');
    } else {
        $(activeSubMenuId).hide();
        $(obj).removeClassName('open');
    }
}

function abserveResetMobileMenuState()
{
    if ($('menu-content') != undefined) $('menu-content').hide();
    $$('.abserve-custom-menu-submenu').each(function(item) {
        $(item).hide();
    });
    if ($('custommenu-mobile') != undefined) {
        $('custommenu-mobile').select('span.button').each(function(item) {
            $(item).removeClassName('open');
        });
    }
}

function abserveHoverSubcategory(id,cat_id){
    var id = parseInt(id);
    var img_cat_id = ".img_cat_id_"+id;
    var main_cat_id = ".img_cat_id_"+cat_id;    
    jQuery(".category_image").removeClass("active");
    jQuery(img_cat_id).addClass("active");
    
}

function abserveCustomMenuMobileToggle()
{
    var w = window,
        d = document,
        e = d.documentElement,
        g = d.getElementsByTagName('body')[0],
        x = w.innerWidth || e.clientWidth || g.clientWidth,
        y = w.innerHeight|| e.clientHeight|| g.clientHeight;

    if (abserveMobileMenuEnabled && CUSTOMMENU_MOBILE_MENU_WIDTH_INIT > x) {

        abserveInitMobileMenuContent();
        if ($('custommenu') != undefined) $('custommenu').hide();
        if ($('custommenu-mobile') != undefined) $('custommenu-mobile').show();
        // --- ajax load ---
        if (abserveMoblieMenuAjaxUrl) {
            new Ajax.Request(
                abserveMoblieMenuAjaxUrl, {
                    asynchronous: true,
                    method: 'post',
                    onSuccess: function(transport) {
                        if (transport && transport.responseText) {
                            try {
                                response = eval('(' + transport.responseText + ')');
                            } catch (e) {
                                response = {};
                            }
                        }
                        abserveMobileMenuContent = response;
                        abserveMobileMenuLoaded = false;
                        abserveInitMobileMenuContent();
                    }
                }
            );
            abserveMoblieMenuAjaxUrl = null;
        }

    } else {

        if ($('custommenu-mobile') != undefined) $('custommenu-mobile').hide();
        abserveResetMobileMenuState();
        if ($('custommenu') != undefined) $('custommenu').show();
        // --- ajax load ---
        if (abserveMenuAjaxUrl) {
            new Ajax.Request(
                abserveMenuAjaxUrl, {
                    asynchronous: true,
                    method: 'post',
                    onSuccess: function(transport) {
                        if (transport && transport.responseText) {
                            try {
                                response = eval('(' + transport.responseText + ')');
                            } catch (e) {
                                response = {};
                            }
                        }
                        if ($('custommenu') != undefined) $('custommenu').update(response.topMenu);
                        abservePopupMenuContent = response.popupMenu;
                    }
                }
            );
            abserveMenuAjaxUrl = null;
        }

    }

    if ($('custommenu-loading') != undefined) $('custommenu-loading').remove();
}
