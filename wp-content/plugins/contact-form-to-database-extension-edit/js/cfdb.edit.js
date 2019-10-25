/*
 "Contact Form to Database Extension Edit" Copyright (C) 2011-2015 Simpson Software Studio LLC (email : info@simpson-software-studio.com)

 This file is part of Contact Form to Database Extension Edit.

 Contact Form to Database Extension Edit is licensed under the terms of an End User License Agreement (EULA).
 You should have received a copy of the license along with Contact Form to Database Extension Edit
 (See the license.txt file).
 */

/**
 * @deprecated
 */
function cfdbEditable(tableHtmlId, cfdbEditUrl, cfdbGetValueUrl, cfdbColEditUrl, loadImg) {
    cfdbEditable(tableHtmlId, cfdbEditUrl, cfdbGetValueUrl, cfdbColEditUrl, null, loadImg);
}

/**
 * Admin grid view and [cfdb-datatable]
 * @param tableHtmlId html id of table element containing data to make it editable
 * @param cfdbEditUrl url to write back the new value in the table cell text area
 * @param cfdbGetValueUrl url to fetch the value of the cell into the editable textarea
 * @param cfdbColEditUrl url to write back the new value in the table column text area
 * @param cfdbGetColumnValueUrl url to fetch the value of the column into the editable textarea
 * @param loadImg image to display while fetching/saving data
 */
function cfdbEditable(tableHtmlId, cfdbEditUrl, cfdbGetValueUrl, cfdbColEditUrl, cfdbGetColumnValueUrl, loadImg) {
    cfdbEditableCells(tableHtmlId, cfdbEditUrl, cfdbGetValueUrl, loadImg);
    cfdbEditableColumnHeaders(tableHtmlId, cfdbColEditUrl, cfdbGetColumnValueUrl, loadImg);
}

function cfdbIsHTML(str) {
    var a = document.createElement('div');
    a.innerHTML = str;
    for (var c = a.childNodes, i = c.length; i--;) {
        if (c[i].nodeType == 1) return true;
    }
    return false;
}

/**
 * Admin grid view and [cfdb-datatable]
 * @param tableHtmlId html id of table element containing data to make it editable
 * @param cfdbEditUrl url to write back the new value in the table cell text area
 * @param cfdbGetValueUrl url to fetch the value of the cell into the editable textarea
 * @param loadImg image to display while fetching/saving data
 */
function cfdbEditableCells(tableHtmlId, cfdbEditUrl, cfdbGetValueUrl, loadImg) {
    jQuery.each(jQuery('#' + tableHtmlId).find('td:not([title="Submitted"]) > div'), function () {
        var self = jQuery(this);
        var loadUrl = cfdbIsHTML(self.html()) ? cfdbGetValueUrl : null;
        self.editable(
                cfdbEditUrl,
                {
                    type: 'textarea',
                    submit: 'OK',
                    indicator: '<img alt="Saving..." src="' + loadImg + '"/>',
                    height: '50px',
                    placeholder: '&nbsp;',
                    select: 'true',
                    ajaxoptions: {
                        cache: false
                    },
                    loadurl: loadUrl,
                    onerror: function (settings, original, xhr) {
                        alert("It wasn't possible to edit. Please try again. Status code: " + xhr.status);
                        console.log("XHR Status: " + xhr.status);
                    },
                    callback: function (value, settings) {
                        console.log(this);
                        console.log(value);
                        console.log(settings);
                    }
                }
        );
    });

}

/**
 * Admin grid view and [cfdb-datatable]
 * @param tableHtmlId html id of table element containing data to make it editable
 * @param cfdbColEditUrl url to write back the new value in the table column text area
 * @param cfdbGetColumnValueUrl url to fetch the value of the column into the editable textarea
 * @param loadImg image to display while fetching/saving data
 */
function cfdbEditableColumnHeaders(tableHtmlId, cfdbColEditUrl, cfdbGetColumnValueUrl, loadImg) {
    jQuery('#' + tableHtmlId + '_wrapper').find('th:not([title="Submitted"]) > div > div').editable(
            cfdbColEditUrl,
            {
                type: 'textarea',
                submit: 'OK',
                indicator: '<img alt="Saving..." src="' + loadImg + '"/>',
                height: '50px',
                placeholder: '&nbsp;',
                select: 'true',
                ajaxoptions: {
                    cache: false,
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert(textStatus + ": " + errorThrown);
                    }
                    //,complete: function (jqXHR, textStatus) {
                    //    alert(textStatus);
                    //}
                },
                loadurl: cfdbGetColumnValueUrl,
                onerror : function(settings,original,xhr){
                    alert("It wasn't possible to edit. Please try again. Status code: " + xhr.status);
                    console.log("XHR Status: " + xhr.status);
                },
                callback: function (newColumnName, settings) {
                    var origColumnName = this.id.match(/,.*$/)[0].substring(1);
                    if (origColumnName != newColumnName) {
                        this.id = this.id.replace("," + origColumnName, "," + newColumnName);
                        jQuery('#' + tableHtmlId + ' td').each(function () {
                            if (jQuery(this).attr("title") == origColumnName) {
                                jQuery(this).attr("title", newColumnName);
                            }
                        });
                        jQuery('#' + tableHtmlId + ' td > div').each(function () {
                            var pos = this.id.indexOf(origColumnName);
                            if ((this.id.length - pos) == origColumnName.length) { // ends with
                                this.id = this.id.substr(0, this.id.length - origColumnName.length) + newColumnName;
                            }
                        });
                    }
                }
            });
}

/**
 * Admin single-entry view
 * @param tableHtmlId html id of table element containing data to make it editable
 * @param cfdbEditUrl url to write back the new value in the table cell text area
 * @param cfdbGetValueUrl url to fetch the value of the cell into the editable textarea
 * @param loadImg image to display while fetching/saving data
 */
function cfdbEntryEditable(tableHtmlId, cfdbEditUrl, cfdbGetValueUrl, loadImg) {
    (function ($) {
        $("#" + tableHtmlId).find("tr:not(:first-child) td:nth-child(2) div").editable(
                cfdbEditUrl,
                {
                    type: 'textarea',
                    submit: 'OK',
                    indicator: '<img alt="Saving..." src="' + loadImg + '"/>',
                    height: '50px',
                    placeholder: '&nbsp;',
                    select: 'true',
                    ajaxoptions: {
                        cache: false,
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert(textStatus + ": " + errorThrown);
                        }
                        //,complete: function (jqXHR, textStatus) {
                        //    alert(textStatus);
                        //}
                    },
                    loadurl: cfdbGetValueUrl,
                    onerror : function(settings,original,xhr){
                        alert("It wasn't possible to edit. Please try again. Status code: " + xhr.status);
                        console.log("XHR Status: " + xhr.status);
                    },
                    callback : function(value, settings) {
                        console.log(this);
                        console.log(value);
                        console.log(settings);
                    }
                })
    })(jQuery);
}
