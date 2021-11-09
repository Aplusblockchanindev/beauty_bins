/*!
 * jQuery Form Plugin
 * version: 3.51.0-2014.06.20
 * Requires jQuery v1.5 or later
 * Copyright (c) 2014 M. Alsup
 * Examples and documentation at: http://malsup.com/jquery/form/
 * Project repository: https://github.com/malsup/form
 * Dual licensed under the MIT and GPL licenses.
 * https://github.com/malsup/form#copyright-and-license
 */
!function(e){"use strict";"function"==typeof define&&define.amd?define(["jquery"],e):e("undefined"!=typeof jQuery?jQuery:window.Zepto)}(function(e){"use strict";function t(t){var r=t.data;t.isDefaultPrevented()||(t.preventDefault(),e(t.target).ajaxSubmit(r))}function r(t){var r=t.target,a=e(r);if(!a.is("[type=submit],[type=image]")){var n=a.closest("[type=submit]");if(0===n.length)return;r=n[0]}var i=this;if(i.clk=r,"image"==r.type)if(void 0!==t.offsetX)i.clk_x=t.offsetX,i.clk_y=t.offsetY;else if("function"==typeof e.fn.offset){var o=a.offset();i.clk_x=t.pageX-o.left,i.clk_y=t.pageY-o.top}else i.clk_x=t.pageX-r.offsetLeft,i.clk_y=t.pageY-r.offsetTop;setTimeout(function(){i.clk=i.clk_x=i.clk_y=null},100)}function a(){if(e.fn.ajaxSubmit.debug){var t="[jquery.form] "+Array.prototype.join.call(arguments,"");window.console&&window.console.log?window.console.log(t):window.opera&&window.opera.postError&&window.opera.postError(t)}}var n={};n.fileapi=void 0!==e("<input type='file'/>").get(0).files,n.formdata=void 0!==window.FormData;var i=!!e.fn.prop;e.fn.attr2=function(){if(!i)return this.attr.apply(this,arguments);var e=this.prop.apply(this,arguments);return e&&e.jquery||"string"==typeof e?e:this.attr.apply(this,arguments)},e.fn.ajaxSubmit=function(t){function r(r){var a,n,i=e.param(r,t.traditional).split("&"),o=i.length,s=[];for(a=0;o>a;a++)i[a]=i[a].replace(/\+/g," "),n=i[a].split("="),s.push([decodeURIComponent(n[0]),decodeURIComponent(n[1])]);return s}function o(a){for(var n=new FormData,i=0;i<a.length;i++)n.append(a[i].name,a[i].value);if(t.extraData){var o=r(t.extraData);for(i=0;i<o.length;i++)o[i]&&n.append(o[i][0],o[i][1])}t.data=null;var s=e.extend(!0,{},e.ajaxSettings,t,{contentType:!1,processData:!1,cache:!1,type:u||"POST"});t.uploadProgress&&(s.xhr=function(){var r=e.ajaxSettings.xhr();return r.upload&&r.upload.addEventListener("progress",function(e){var r=0,a=e.loaded||e.position,n=e.total;e.lengthComputable&&(r=Math.ceil(a/n*100)),t.uploadProgress(e,a,n,r)},!1),r}),s.data=null;var c=s.beforeSend;return s.beforeSend=function(e,r){r.data=t.formData?t.formData:n,c&&c.call(this,e,r)},e.ajax(s)}function s(r){function n(e){var t=null;try{e.contentWindow&&(t=e.contentWindow.document)}catch(r){a("cannot get iframe.contentWindow document: "+r)}if(t)return t;try{t=e.contentDocument?e.contentDocument:e.document}catch(r){a("cannot get iframe.contentDocument: "+r),t=e.document}return t}function o(){function t(){try{var e=n(g).readyState;a("state = "+e),e&&"uninitialized"==e.toLowerCase()&&setTimeout(t,50)}catch(r){a("Server abort: ",r," (",r.name,")"),s(k),j&&clearTimeout(j),j=void 0}}var r=f.attr2("target"),i=f.attr2("action"),o="multipart/form-data",c=f.attr("enctype")||f.attr("encoding")||o;w.setAttribute("target",p),(!u||/post/i.test(u))&&w.setAttribute("method","POST"),i!=m.url&&w.setAttribute("action",m.url),m.skipEncodingOverride||u&&!/post/i.test(u)||f.attr({encoding:"multipart/form-data",enctype:"multipart/form-data"}),m.timeout&&(j=setTimeout(function(){T=!0,s(D)},m.timeout));var l=[];try{if(m.extraData)for(var d in m.extraData)m.extraData.hasOwnProperty(d)&&l.push(e.isPlainObject(m.extraData[d])&&m.extraData[d].hasOwnProperty("name")&&m.extraData[d].hasOwnProperty("value")?e('<input type="hidden" name="'+m.extraData[d].name+'">').val(m.extraData[d].value).appendTo(w)[0]:e('<input type="hidden" name="'+d+'">').val(m.extraData[d]).appendTo(w)[0]);m.iframeTarget||v.appendTo("body"),g.attachEvent?g.attachEvent("onload",s):g.addEventListener("load",s,!1),setTimeout(t,15);try{w.submit()}catch(h){var x=document.createElement("form").submit;x.apply(w)}}finally{w.setAttribute("action",i),w.setAttribute("enctype",c),r?w.setAttribute("target",r):f.removeAttr("target"),e(l).remove()}}function s(t){if(!x.aborted&&!F){if(M=n(g),M||(a("cannot access response document"),t=k),t===D&&x)return x.abort("timeout"),void S.reject(x,"timeout");if(t==k&&x)return x.abort("server abort"),void S.reject(x,"error","server abort");if(M&&M.location.href!=m.iframeSrc||T){g.detachEvent?g.detachEvent("onload",s):g.removeEventListener("load",s,!1);var r,i="success";try{if(T)throw"timeout";var o="xml"==m.dataType||M.XMLDocument||e.isXMLDoc(M);if(a("isXml="+o),!o&&window.opera&&(null===M.body||!M.body.innerHTML)&&--O)return a("requeing onLoad callback, DOM not available"),void setTimeout(s,250);var u=M.body?M.body:M.documentElement;x.responseText=u?u.innerHTML:null,x.responseXML=M.XMLDocument?M.XMLDocument:M,o&&(m.dataType="xml"),x.getResponseHeader=function(e){var t={"content-type":m.dataType};return t[e.toLowerCase()]},u&&(x.status=Number(u.getAttribute("status"))||x.status,x.statusText=u.getAttribute("statusText")||x.statusText);var c=(m.dataType||"").toLowerCase(),l=/(json|script|text)/.test(c);if(l||m.textarea){var f=M.getElementsByTagName("textarea")[0];if(f)x.responseText=f.value,x.status=Number(f.getAttribute("status"))||x.status,x.statusText=f.getAttribute("statusText")||x.statusText;else if(l){var p=M.getElementsByTagName("pre")[0],h=M.getElementsByTagName("body")[0];p?x.responseText=p.textContent?p.textContent:p.innerText:h&&(x.responseText=h.textContent?h.textContent:h.innerText)}}else"xml"==c&&!x.responseXML&&x.responseText&&(x.responseXML=X(x.responseText));try{E=_(x,c,m)}catch(y){i="parsererror",x.error=r=y||i}}catch(y){a("error caught: ",y),i="error",x.error=r=y||i}x.aborted&&(a("upload aborted"),i=null),x.status&&(i=x.status>=200&&x.status<300||304===x.status?"success":"error"),"success"===i?(m.success&&m.success.call(m.context,E,"success",x),S.resolve(x.responseText,"success",x),d&&e.event.trigger("ajaxSuccess",[x,m])):i&&(void 0===r&&(r=x.statusText),m.error&&m.error.call(m.context,x,i,r),S.reject(x,"error",r),d&&e.event.trigger("ajaxError",[x,m,r])),d&&e.event.trigger("ajaxComplete",[x,m]),d&&!--e.active&&e.event.trigger("ajaxStop"),m.complete&&m.complete.call(m.context,x,i),F=!0,m.timeout&&clearTimeout(j),setTimeout(function(){m.iframeTarget?v.attr("src",m.iframeSrc):v.remove(),x.responseXML=null},100)}}}var c,l,m,d,p,v,g,x,y,b,T,j,w=f[0],S=e.Deferred();if(S.abort=function(e){x.abort(e)},r)for(l=0;l<h.length;l++)c=e(h[l]),i?c.prop("disabled",!1):c.removeAttr("disabled");if(m=e.extend(!0,{},e.ajaxSettings,t),m.context=m.context||m,p="jqFormIO"+(new Date).getTime(),m.iframeTarget?(v=e(m.iframeTarget),b=v.attr2("name"),b?p=b:v.attr2("name",p)):(v=e('<iframe name="'+p+'" src="'+m.iframeSrc+'" />'),v.css({position:"absolute",top:"-1000px",left:"-1000px"})),g=v[0],x={aborted:0,responseText:null,responseXML:null,status:0,statusText:"n/a",getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(t){var r="timeout"===t?"timeout":"aborted";a("aborting upload... "+r),this.aborted=1;try{g.contentWindow.document.execCommand&&g.contentWindow.document.execCommand("Stop")}catch(n){}v.attr("src",m.iframeSrc),x.error=r,m.error&&m.error.call(m.context,x,r,t),d&&e.event.trigger("ajaxError",[x,m,r]),m.complete&&m.complete.call(m.context,x,r)}},d=m.global,d&&0===e.active++&&e.event.trigger("ajaxStart"),d&&e.event.trigger("ajaxSend",[x,m]),m.beforeSend&&m.beforeSend.call(m.context,x,m)===!1)return m.global&&e.active--,S.reject(),S;if(x.aborted)return S.reject(),S;y=w.clk,y&&(b=y.name,b&&!y.disabled&&(m.extraData=m.extraData||{},m.extraData[b]=y.value,"image"==y.type&&(m.extraData[b+".x"]=w.clk_x,m.extraData[b+".y"]=w.clk_y)));var D=1,k=2,A=e("meta[name=csrf-token]").attr("content"),L=e("meta[name=csrf-param]").attr("content");L&&A&&(m.extraData=m.extraData||{},m.extraData[L]=A),m.forceSync?o():setTimeout(o,10);var E,M,F,O=50,X=e.parseXML||function(e,t){return window.ActiveXObject?(t=new ActiveXObject("Microsoft.XMLDOM"),t.async="false",t.loadXML(e)):t=(new DOMParser).parseFromString(e,"text/xml"),t&&t.documentElement&&"parsererror"!=t.documentElement.nodeName?t:null},C=e.parseJSON||function(e){return window.eval("("+e+")")},_=function(t,r,a){var n=t.getResponseHeader("content-type")||"",i="xml"===r||!r&&n.indexOf("xml")>=0,o=i?t.responseXML:t.responseText;return i&&"parsererror"===o.documentElement.nodeName&&e.error&&e.error("parsererror"),a&&a.dataFilter&&(o=a.dataFilter(o,r)),"string"==typeof o&&("json"===r||!r&&n.indexOf("json")>=0?o=C(o):("script"===r||!r&&n.indexOf("javascript")>=0)&&e.globalEval(o)),o};return S}if(!this.length)return a("ajaxSubmit: skipping submit process - no element selected"),this;var u,c,l,f=this;"function"==typeof t?t={success:t}:void 0===t&&(t={}),u=t.type||this.attr2("method"),c=t.url||this.attr2("action"),l="string"==typeof c?e.trim(c):"",l=l||window.location.href||"",l&&(l=(l.match(/^([^#]+)/)||[])[1]),t=e.extend(!0,{url:l,success:e.ajaxSettings.success,type:u||e.ajaxSettings.type,iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank"},t);var m={};if(this.trigger("form-pre-serialize",[this,t,m]),m.veto)return a("ajaxSubmit: submit vetoed via form-pre-serialize trigger"),this;if(t.beforeSerialize&&t.beforeSerialize(this,t)===!1)return a("ajaxSubmit: submit aborted via beforeSerialize callback"),this;var d=t.traditional;void 0===d&&(d=e.ajaxSettings.traditional);var p,h=[],v=this.formToArray(t.semantic,h);if(t.data&&(t.extraData=t.data,p=e.param(t.data,d)),t.beforeSubmit&&t.beforeSubmit(v,this,t)===!1)return a("ajaxSubmit: submit aborted via beforeSubmit callback"),this;if(this.trigger("form-submit-validate",[v,this,t,m]),m.veto)return a("ajaxSubmit: submit vetoed via form-submit-validate trigger"),this;var g=e.param(v,d);p&&(g=g?g+"&"+p:p),"GET"==t.type.toUpperCase()?(t.url+=(t.url.indexOf("?")>=0?"&":"?")+g,t.data=null):t.data=g;var x=[];if(t.resetForm&&x.push(function(){f.resetForm()}),t.clearForm&&x.push(function(){f.clearForm(t.includeHidden)}),!t.dataType&&t.target){var y=t.success||function(){};x.push(function(r){var a=t.replaceTarget?"replaceWith":"html";e(t.target)[a](r).each(y,arguments)})}else t.success&&x.push(t.success);if(t.success=function(e,r,a){for(var n=t.context||this,i=0,o=x.length;o>i;i++)x[i].apply(n,[e,r,a||f,f])},t.error){var b=t.error;t.error=function(e,r,a){var n=t.context||this;b.apply(n,[e,r,a,f])}}if(t.complete){var T=t.complete;t.complete=function(e,r){var a=t.context||this;T.apply(a,[e,r,f])}}var j=e("input[type=file]:enabled",this).filter(function(){return""!==e(this).val()}),w=j.length>0,S="multipart/form-data",D=f.attr("enctype")==S||f.attr("encoding")==S,k=n.fileapi&&n.formdata;a("fileAPI :"+k);var A,L=(w||D)&&!k;t.iframe!==!1&&(t.iframe||L)?t.closeKeepAlive?e.get(t.closeKeepAlive,function(){A=s(v)}):A=s(v):A=(w||D)&&k?o(v):e.ajax(t),f.removeData("jqxhr").data("jqxhr",A);for(var E=0;E<h.length;E++)h[E]=null;return this.trigger("form-submit-notify",[this,t]),this},e.fn.ajaxForm=function(n){if(n=n||{},n.delegation=n.delegation&&e.isFunction(e.fn.on),!n.delegation&&0===this.length){var i={s:this.selector,c:this.context};return!e.isReady&&i.s?(a("DOM not ready, queuing ajaxForm"),e(function(){e(i.s,i.c).ajaxForm(n)}),this):(a("terminating; zero elements found by selector"+(e.isReady?"":" (DOM not ready)")),this)}return n.delegation?(e(document).off("submit.form-plugin",this.selector,t).off("click.form-plugin",this.selector,r).on("submit.form-plugin",this.selector,n,t).on("click.form-plugin",this.selector,n,r),this):this.ajaxFormUnbind().bind("submit.form-plugin",n,t).bind("click.form-plugin",n,r)},e.fn.ajaxFormUnbind=function(){return this.unbind("submit.form-plugin click.form-plugin")},e.fn.formToArray=function(t,r){var a=[];if(0===this.length)return a;var i,o=this[0],s=this.attr("id"),u=t?o.getElementsByTagName("*"):o.elements;if(u&&!/MSIE [678]/.test(navigator.userAgent)&&(u=e(u).get()),s&&(i=e(':input[form="'+s+'"]').get(),i.length&&(u=(u||[]).concat(i))),!u||!u.length)return a;var c,l,f,m,d,p,h;for(c=0,p=u.length;p>c;c++)if(d=u[c],f=d.name,f&&!d.disabled)if(t&&o.clk&&"image"==d.type)o.clk==d&&(a.push({name:f,value:e(d).val(),type:d.type}),a.push({name:f+".x",value:o.clk_x},{name:f+".y",value:o.clk_y}));else if(m=e.fieldValue(d,!0),m&&m.constructor==Array)for(r&&r.push(d),l=0,h=m.length;h>l;l++)a.push({name:f,value:m[l]});else if(n.fileapi&&"file"==d.type){r&&r.push(d);var v=d.files;if(v.length)for(l=0;l<v.length;l++)a.push({name:f,value:v[l],type:d.type});else a.push({name:f,value:"",type:d.type})}else null!==m&&"undefined"!=typeof m&&(r&&r.push(d),a.push({name:f,value:m,type:d.type,required:d.required}));if(!t&&o.clk){var g=e(o.clk),x=g[0];f=x.name,f&&!x.disabled&&"image"==x.type&&(a.push({name:f,value:g.val()}),a.push({name:f+".x",value:o.clk_x},{name:f+".y",value:o.clk_y}))}return a},e.fn.formSerialize=function(t){return e.param(this.formToArray(t))},e.fn.fieldSerialize=function(t){var r=[];return this.each(function(){var a=this.name;if(a){var n=e.fieldValue(this,t);if(n&&n.constructor==Array)for(var i=0,o=n.length;o>i;i++)r.push({name:a,value:n[i]});else null!==n&&"undefined"!=typeof n&&r.push({name:this.name,value:n})}}),e.param(r)},e.fn.fieldValue=function(t){for(var r=[],a=0,n=this.length;n>a;a++){var i=this[a],o=e.fieldValue(i,t);null===o||"undefined"==typeof o||o.constructor==Array&&!o.length||(o.constructor==Array?e.merge(r,o):r.push(o))}return r},e.fieldValue=function(t,r){var a=t.name,n=t.type,i=t.tagName.toLowerCase();if(void 0===r&&(r=!0),r&&(!a||t.disabled||"reset"==n||"button"==n||("checkbox"==n||"radio"==n)&&!t.checked||("submit"==n||"image"==n)&&t.form&&t.form.clk!=t||"select"==i&&-1==t.selectedIndex))return null;if("select"==i){var o=t.selectedIndex;if(0>o)return null;for(var s=[],u=t.options,c="select-one"==n,l=c?o+1:u.length,f=c?o:0;l>f;f++){var m=u[f];if(m.selected){var d=m.value;if(d||(d=m.attributes&&m.attributes.value&&!m.attributes.value.specified?m.text:m.value),c)return d;s.push(d)}}return s}return e(t).val()},e.fn.clearForm=function(t){return this.each(function(){e("input,select,textarea",this).clearFields(t)})},e.fn.clearFields=e.fn.clearInputs=function(t){var r=/^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;return this.each(function(){var a=this.type,n=this.tagName.toLowerCase();r.test(a)||"textarea"==n?this.value="":"checkbox"==a||"radio"==a?this.checked=!1:"select"==n?this.selectedIndex=-1:"file"==a?/MSIE/.test(navigator.userAgent)?e(this).replaceWith(e(this).clone(!0)):e(this).val(""):t&&(t===!0&&/hidden/.test(a)||"string"==typeof t&&e(this).is(t))&&(this.value="")})},e.fn.resetForm=function(){return this.each(function(){("function"==typeof this.reset||"object"==typeof this.reset&&!this.reset.nodeType)&&this.reset()})},e.fn.enable=function(e){return void 0===e&&(e=!0),this.each(function(){this.disabled=!e})},e.fn.selected=function(t){return void 0===t&&(t=!0),this.each(function(){var r=this.type;if("checkbox"==r||"radio"==r)this.checked=t;else if("option"==this.tagName.toLowerCase()){var a=e(this).parent("select");t&&a[0]&&"select-one"==a[0].type&&a.find("option").selected(!1),this.selected=t}})},e.fn.ajaxSubmit.debug=!1});/*!
 * bootstrap-tokenfield
 * https://github.com/sliptree/bootstrap-tokenfield
 * Copyright 2013-2014 Sliptree and other contributors; Licensed MIT
 */

(function (factory) {
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module.
    define(['jquery'], factory);
  } else if (typeof exports === 'object') {
    // For CommonJS and CommonJS-like environments where a window with jQuery
    // is present, execute the factory with the jQuery instance from the window object
    // For environments that do not inherently posses a window with a document
    // (such as Node.js), expose a Tokenfield-making factory as module.exports
    // This accentuates the need for the creation of a real window or passing in a jQuery instance
    // e.g. require("bootstrap-tokenfield")(window); or require("bootstrap-tokenfield")($);
    module.exports = global.window && global.window.$ ?
      factory( global.window.$ ) :
      function( input ) {
        if ( !input.$ && !input.fn ) {
          throw new Error( "Tokenfield requires a window object with jQuery or a jQuery instance" );
        }
        return factory( input.$ || input );
      };
  } else {
    // Browser globals
    factory(jQuery, window);
  }
}(function ($, window) {

  "use strict"; // jshint ;_;

 /* TOKENFIELD PUBLIC CLASS DEFINITION
  * ============================== */

  var Tokenfield = function (element, options) {
    var _self = this

    this.$element = $(element)
    this.textDirection = this.$element.css('direction');

    // Extend options
    this.options = $.extend(true, {}, $.fn.tokenfield.defaults, { tokens: this.$element.val() }, this.$element.data(), options)

    // Setup delimiters and trigger keys
    this._delimiters = (typeof this.options.delimiter === 'string') ? [this.options.delimiter] : this.options.delimiter
    this._triggerKeys = $.map(this._delimiters, function (delimiter) {
      return delimiter.charCodeAt(0);
    });
    this._firstDelimiter = this._delimiters[0];

    // Check for whitespace, dash and special characters
    var whitespace = $.inArray(' ', this._delimiters)
      , dash = $.inArray('-', this._delimiters)

    if (whitespace >= 0)
      this._delimiters[whitespace] = '\\s'

    if (dash >= 0) {
      delete this._delimiters[dash]
      this._delimiters.unshift('-')
    }

    var specialCharacters = ['\\', '$', '[', '{', '^', '.', '|', '?', '*', '+', '(', ')']
    $.each(this._delimiters, function (index, character) {
      var pos = $.inArray(character, specialCharacters)
      if (pos >= 0) _self._delimiters[index] = '\\' + character;
    });

    // Store original input width
    var elRules = (window && typeof window.getMatchedCSSRules === 'function') ? window.getMatchedCSSRules( element ) : null
      , elStyleWidth = element.style.width
      , elCSSWidth
      , elWidth = this.$element.width()

    if (elRules) {
      $.each( elRules, function (i, rule) {
        if (rule.style.width) {
          elCSSWidth = rule.style.width;
        }
      });
    }

    // Move original input out of the way
    var hidingPosition = $('body').css('direction') === 'rtl' ? 'right' : 'left',
        originalStyles = { position: this.$element.css('position') };
    originalStyles[hidingPosition] = this.$element.css(hidingPosition);

    this.$element
      .data('original-styles', originalStyles)
      .data('original-tabindex', this.$element.prop('tabindex'))
      .css('position', 'absolute')
      .css(hidingPosition, '-10000px')
      .prop('tabindex', -1)

    // Create a wrapper
    this.$wrapper = $('<div class="tokenfield ing-form-control" />')
    if (this.$element.hasClass('input-lg')) this.$wrapper.addClass('input-lg')
    if (this.$element.hasClass('input-sm')) this.$wrapper.addClass('input-sm')
    if (this.textDirection === 'rtl') this.$wrapper.addClass('rtl')

    // Create a new input
    var id = this.$element.prop('id') || new Date().getTime() + '' + Math.floor((1 + Math.random()) * 100)
    this.$input = $('<input type="'+this.options.inputType+'" class="token-input" autocomplete="off" />')
                    .appendTo( this.$wrapper )
                    .prop( 'placeholder',  this.$element.prop('placeholder') )
                    .prop( 'id', id + '-tokenfield' )
                    .prop( 'tabindex', this.$element.data('original-tabindex') )

    // Re-route original input label to new input
    var $label = $( 'label[for="' + this.$element.prop('id') + '"]' )
    if ( $label.length ) {
      $label.prop( 'for', this.$input.prop('id') )
    }

    // Set up a copy helper to handle copy & paste
    this.$copyHelper = $('<input type="text" />').css('position', 'absolute').css(hidingPosition, '-10000px').prop('tabindex', -1).prependTo( this.$wrapper )

    // Set wrapper width
    if (elStyleWidth) {
      this.$wrapper.css('width', elStyleWidth);
    }
    else if (elCSSWidth) {
      this.$wrapper.css('width', elCSSWidth);
    }
    // If input is inside inline-form with no width set, set fixed width
    else if (this.$element.parents('.form-inline').length) {
      this.$wrapper.width( elWidth )
    }

    // Set tokenfield disabled, if original or fieldset input is disabled
    if (this.$element.prop('disabled') || this.$element.parents('fieldset[disabled]').length) {
      this.disable();
    }

    // Set tokenfield readonly, if original input is readonly
    if (this.$element.prop('readonly')) {
      this.readonly();
    }

    // Set up mirror for input auto-sizing
    this.$mirror = $('<span style="position:absolute; top:-999px; left:0; white-space:pre;"/>');
    this.$input.css('min-width', this.options.minWidth + 'px')
    $.each([
        'fontFamily',
        'fontSize',
        'fontWeight',
        'fontStyle',
        'letterSpacing',
        'textTransform',
        'wordSpacing',
        'textIndent'
    ], function (i, val) {
        _self.$mirror[0].style[val] = _self.$input.css(val);
    });
    this.$mirror.appendTo( 'body' )

    // Insert tokenfield to HTML
    this.$wrapper.insertBefore( this.$element )
    this.$element.prependTo( this.$wrapper )

    // Calculate inner input width
    this.update()

    // Create initial tokens, if any
    this.setTokens(this.options.tokens, false, ! this.$element.val() && this.options.tokens )

    // Start listening to events
    this.listen()

    // Initialize autocomplete, if necessary
    if ( ! $.isEmptyObject( this.options.autocomplete ) ) {
      var side = this.textDirection === 'rtl' ? 'right' : 'left'
       ,  autocompleteOptions = $.extend({
            minLength: this.options.showAutocompleteOnFocus ? 0 : null,
            position: { my: side + " top", at: side + " bottom", of: this.$wrapper }
          }, this.options.autocomplete )

      this.$input.autocomplete( autocompleteOptions )
    }

    // Initialize typeahead, if necessary
    if ( ! $.isEmptyObject( this.options.typeahead ) ) {

      var typeaheadOptions = this.options.typeahead
        , defaults = {
            minLength: this.options.showAutocompleteOnFocus ? 0 : null
          }
        , args = $.isArray( typeaheadOptions ) ? typeaheadOptions : [typeaheadOptions, typeaheadOptions]

      args[0] = $.extend( {}, defaults, args[0] )

      this.$input.typeahead.apply( this.$input, args )
      this.typeahead = true
    }
  }

  Tokenfield.prototype = {

    constructor: Tokenfield

  , createToken: function (attrs, triggerChange) {
      var _self = this

      if (typeof attrs === 'string') {
        attrs = { value: attrs, label: attrs }
      } else {
        // Copy objects to prevent contamination of data sources.
        attrs = $.extend( {}, attrs )
      }

      if (typeof triggerChange === 'undefined') {
         triggerChange = true
      }

      // Normalize label and value
      attrs.value = $.trim(attrs.value.toString());
      attrs.label = attrs.label && attrs.label.length ? $.trim(attrs.label) : attrs.value

      // Bail out if has no value or label, or label is too short
      if (!attrs.value.length || !attrs.label.length || attrs.label.length <= this.options.minLength) return

      // Bail out if maximum number of tokens is reached
      if (this.options.limit && this.getTokens().length >= this.options.limit) return

      // Allow changing token data before creating it
      var createEvent = $.Event('tokenfield:createtoken', { attrs: attrs })
      this.$element.trigger(createEvent)

      // Bail out if there if attributes are empty or event was defaultPrevented
      if (!createEvent.attrs || createEvent.isDefaultPrevented()) return

      var $token = $('<div class="token" />')
            .append('<span class="token-label" />')
            .append('<a href="#" class="close" tabindex="-1">&times;</a>')
            .data('attrs', attrs)

      // Insert token into HTML
      if (this.$input.hasClass('tt-input')) {
        // If the input has typeahead enabled, insert token before it's parent
        this.$input.parent().before( $token )
      } else {
        this.$input.before( $token )
      }

      // Temporarily set input width to minimum
      //this.$input.css('width', this.options.minWidth + 'px')

      var $tokenLabel = $token.find('.token-label')
        , $closeButton = $token.find('.close')

      // Determine maximum possible token label width
      if (!this.maxTokenWidth) {
        this.maxTokenWidth =
          this.$wrapper.width() - $closeButton.outerWidth() -
          parseInt($closeButton.css('margin-left'), 10) -
          parseInt($closeButton.css('margin-right'), 10) -
          parseInt($token.css('border-left-width'), 10) -
          parseInt($token.css('border-right-width'), 10) -
          parseInt($token.css('padding-left'), 10) -
          parseInt($token.css('padding-right'), 10)
          parseInt($tokenLabel.css('border-left-width'), 10) -
          parseInt($tokenLabel.css('border-right-width'), 10) -
          parseInt($tokenLabel.css('padding-left'), 10) -
          parseInt($tokenLabel.css('padding-right'), 10)
          parseInt($tokenLabel.css('margin-left'), 10) -
          parseInt($tokenLabel.css('margin-right'), 10)
      }

      $tokenLabel.css('max-width', this.maxTokenWidth)
      if (this.options.html)
        $tokenLabel.html(attrs.label)
      else
        $tokenLabel.text(attrs.label)

      // Listen to events on token
      $token
        .on('mousedown',  function (e) {
          if (_self._disabled || _self._readonly) return false
          _self.preventDeactivation = true
        })
        .on('click',    function (e) {
          if (_self._disabled || _self._readonly) return false
          _self.preventDeactivation = false

          if (e.ctrlKey || e.metaKey) {
            e.preventDefault()
            return _self.toggle( $token )
          }

          _self.activate( $token, e.shiftKey, e.shiftKey )
        })
        .on('dblclick', function (e) {
          if (_self._disabled || _self._readonly || !_self.options.allowEditing ) return false
          _self.edit( $token )
        })

      $closeButton
          .on('click',  $.proxy(this.remove, this))

      // Trigger createdtoken event on the original field
      // indicating that the token is now in the DOM
      this.$element.trigger($.Event('tokenfield:createdtoken', {
        attrs: attrs,
        relatedTarget: $token.get(0)
      }))

      // Trigger change event on the original field
      if (triggerChange) {
        this.$element.val( this.getTokensList() ).trigger( $.Event('change', { initiator: 'tokenfield' }) )
      }

      // Update tokenfield dimensions
      var _self = this
      setTimeout(function () {
        _self.update()
      }, 0)

      // Return original element
      return this.$element.get(0)
    }

  , setTokens: function (tokens, add, triggerChange) {
      if (!add) this.$wrapper.find('.token').remove()

      if (!tokens) return

      if (typeof triggerChange === 'undefined') {
          triggerChange = true
      }

      if (typeof tokens === 'string') {
        if (this._delimiters.length) {
          // Split based on delimiters
          tokens = tokens.split( new RegExp( '[' + this._delimiters.join('') + ']' ) )
        } else {
          tokens = [tokens];
        }
      }

      var _self = this
      $.each(tokens, function (i, attrs) {
        _self.createToken(attrs, triggerChange)
      })

      return this.$element.get(0)
    }

  , getTokenData: function($token) {
      var data = $token.map(function() {
        var $token = $(this);
        return $token.data('attrs')
      }).get();

      if (data.length == 1) {
        data = data[0];
      }

      return data;
    }

  , getTokens: function(active) {
      var self = this
        , tokens = []
        , activeClass = active ? '.active' : '' // get active tokens only
      this.$wrapper.find( '.token' + activeClass ).each( function() {
        tokens.push( self.getTokenData( $(this) ) )
      })
      return tokens
  }

  , getTokensList: function(delimiter, beautify, active) {
      delimiter = delimiter || this._firstDelimiter
      beautify = ( typeof beautify !== 'undefined' && beautify !== null ) ? beautify : this.options.beautify

      var separator = delimiter + ( beautify && delimiter !== ' ' ? ' ' : '')
      return $.map( this.getTokens(active), function (token) {
        return token.value
      }).join(separator)
  }

  , getInput: function() {
    return this.$input.val()
  }
      
  , setInput: function (val) {
      if (this.$input.hasClass('tt-input')) {
          // Typeahead acts weird when simply setting input value to empty,
          // so we set the query to empty instead
          this.$input.typeahead('val', val)
      } else {
          this.$input.val(val)
      }
  }

  , listen: function () {
      var _self = this

      this.$element
        .on('change',   $.proxy(this.change, this))

      this.$wrapper
        .on('mousedown',$.proxy(this.focusInput, this))

      this.$input
        .on('focus',    $.proxy(this.focus, this))
        .on('blur',     $.proxy(this.blur, this))
        .on('paste',    $.proxy(this.paste, this))
        .on('keydown',  $.proxy(this.keydown, this))
        .on('keypress', $.proxy(this.keypress, this))
        .on('keyup',    $.proxy(this.keyup, this))

      this.$copyHelper
        .on('focus',    $.proxy(this.focus, this))
        .on('blur',     $.proxy(this.blur, this))
        .on('keydown',  $.proxy(this.keydown, this))
        .on('keyup',    $.proxy(this.keyup, this))

      // Secondary listeners for input width calculation
      this.$input
        .on('keypress', $.proxy(this.update, this))
        .on('keyup',    $.proxy(this.update, this))

      this.$input
        .on('autocompletecreate', function() {
          // Set minimum autocomplete menu width
          var $_menuElement = $(this).data('ui-autocomplete').menu.element

          var minWidth = _self.$wrapper.outerWidth() -
              parseInt( $_menuElement.css('border-left-width'), 10 ) -
              parseInt( $_menuElement.css('border-right-width'), 10 )

          $_menuElement.css( 'min-width', minWidth + 'px' )
        })
        .on('autocompleteselect', function (e, ui) {
          if (_self.createToken( ui.item )) {
            _self.$input.val('')
            if (_self.$input.data( 'edit' )) {
              _self.unedit(true)
            }
          }
          return false
        })
        .on('typeahead:selected typeahead:autocompleted', function (e, datum, dataset) {
          // Create token
          if (_self.createToken( datum )) {
            _self.$input.typeahead('val', '')
            if (_self.$input.data( 'edit' )) {
              _self.unedit(true)
            }
          }
        })

      // Listen to window resize
      $(window).on('resize', $.proxy(this.update, this ))

    }

  , keydown: function (e) {

      if (!this.focused) return

      var _self = this

      switch(e.keyCode) {
        case 8: // backspace
          if (!this.$input.is(document.activeElement)) break
          this.lastInputValue = this.$input.val()
          break

        case 37: // left arrow
          leftRight( this.textDirection === 'rtl' ? 'next': 'prev' )
          break

        case 38: // up arrow
          upDown('prev')
          break

        case 39: // right arrow
          leftRight( this.textDirection === 'rtl' ? 'prev': 'next' )
          break

        case 40: // down arrow
          upDown('next')
          break

        case 65: // a (to handle ctrl + a)
          if (this.$input.val().length > 0 || !(e.ctrlKey || e.metaKey)) break
          this.activateAll()
          e.preventDefault()
          break

        case 9: // tab
        case 13: // enter

          // We will handle creating tokens from autocomplete in autocomplete events
          if (this.$input.data('ui-autocomplete') && this.$input.data('ui-autocomplete').menu.element.find("li:has(a.ui-state-focus), li.ui-state-focus").length) break

          // We will handle creating tokens from typeahead in typeahead events
          if (this.$input.hasClass('tt-input') && this.$wrapper.find('.tt-cursor').length ) break
          if (this.$input.hasClass('tt-input') && this.$wrapper.find('.tt-hint').val() && this.$wrapper.find('.tt-hint').val().length) break

          // Create token
          if (this.$input.is(document.activeElement) && this.$input.val().length || this.$input.data('edit')) {
            return this.createTokensFromInput(e, this.$input.data('edit'));
          }

          // Edit token
          if (e.keyCode === 13) {
            if (!this.$copyHelper.is(document.activeElement) || this.$wrapper.find('.token.active').length !== 1) break
            if (!_self.options.allowEditing) break
            this.edit( this.$wrapper.find('.token.active') )
          }
      }

      function leftRight(direction) {
        if (_self.$input.is(document.activeElement)) {
          if (_self.$input.val().length > 0) return

          direction += 'All'
          var $token = _self.$input.hasClass('tt-input') ? _self.$input.parent()[direction]('.token:first') : _self.$input[direction]('.token:first')
          if (!$token.length) return

          _self.preventInputFocus = true
          _self.preventDeactivation = true

          _self.activate( $token )
          e.preventDefault()

        } else {
          _self[direction]( e.shiftKey )
          e.preventDefault()
        }
      }

      function upDown(direction) {
        if (!e.shiftKey) return

        if (_self.$input.is(document.activeElement)) {
          if (_self.$input.val().length > 0) return

          var $token = _self.$input.hasClass('tt-input') ? _self.$input.parent()[direction + 'All']('.token:first') : _self.$input[direction + 'All']('.token:first')
          if (!$token.length) return

          _self.activate( $token )
        }

        var opposite = direction === 'prev' ? 'next' : 'prev'
          , position = direction === 'prev' ? 'first' : 'last'

        _self.$firstActiveToken[opposite + 'All']('.token').each(function() {
          _self.deactivate( $(this) )
        })

        _self.activate( _self.$wrapper.find('.token:' + position), true, true )
        e.preventDefault()
      }

      this.lastKeyDown = e.keyCode
    }

  , keypress: function(e) {

      // Comma
      if ($.inArray( e.which, this._triggerKeys) !== -1 && this.$input.is(document.activeElement)) {
        if (this.$input.val()) {
          this.createTokensFromInput(e)
        }
        return false;
      }
    }

  , keyup: function (e) {
      this.preventInputFocus = false

      if (!this.focused) return

      switch(e.keyCode) {
        case 8: // backspace
          if (this.$input.is(document.activeElement)) {
            if (this.$input.val().length || this.lastInputValue.length && this.lastKeyDown === 8) break

            this.preventDeactivation = true
            var $prevToken = this.$input.hasClass('tt-input') ? this.$input.parent().prevAll('.token:first') : this.$input.prevAll('.token:first')

            if (!$prevToken.length) break

            this.activate( $prevToken )
          } else {
            this.remove(e)
          }
          break

        case 46: // delete
          this.remove(e, 'next')
          break
      }
      this.lastKeyUp = e.keyCode
    }

  , focus: function (e) {
      this.focused = true
      this.$wrapper.addClass('focus')

      if (this.$input.is(document.activeElement)) {
        this.$wrapper.find('.active').removeClass('active')
        this.$firstActiveToken = null

        if (this.options.showAutocompleteOnFocus) {
          this.search()
        }
      }
    }

  , blur: function (e) {

      this.focused = false
      this.$wrapper.removeClass('focus')

      if (!this.preventDeactivation && !this.$element.is(document.activeElement)) {
        this.$wrapper.find('.active').removeClass('active')
        this.$firstActiveToken = null
      }

      if (!this.preventCreateTokens && (this.$input.data('edit') && !this.$input.is(document.activeElement) || this.options.createTokensOnBlur )) {
        this.createTokensFromInput(e)
      }

      this.preventDeactivation = false
      this.preventCreateTokens = false
    }

  , paste: function (e) {
      var _self = this

      // Add tokens to existing ones
      if (_self.options.allowPasting) {
        setTimeout(function () {
          _self.createTokensFromInput(e)
        }, 1)
      }
    }

  , change: function (e) {
      if ( e.initiator === 'tokenfield' ) return // Prevent loops

      this.setTokens( this.$element.val() )
    }

  , createTokensFromInput: function (e, focus) {
      if (this.$input.val().length < this.options.minLength)
        return // No input, simply return

      var tokensBefore = this.getTokensList()
      this.setTokens( this.$input.val(), true )

      if (tokensBefore == this.getTokensList() && this.$input.val().length)
        return false // No tokens were added, do nothing (prevent form submit)

      this.setInput('')

      if (this.$input.data( 'edit' )) {
        this.unedit(focus)
      }

      return false // Prevent form being submitted
    }

  , next: function (add) {
      if (add) {
        var $firstActiveToken = this.$wrapper.find('.active:first')
          , deactivate = $firstActiveToken && this.$firstActiveToken ? $firstActiveToken.index() < this.$firstActiveToken.index() : false

        if (deactivate) return this.deactivate( $firstActiveToken )
      }

      var $lastActiveToken = this.$wrapper.find('.active:last')
        , $nextToken = $lastActiveToken.nextAll('.token:first')

      if (!$nextToken.length) {
        this.$input.focus()
        return
      }

      this.activate($nextToken, add)
    }

  , prev: function (add) {

      if (add) {
        var $lastActiveToken = this.$wrapper.find('.active:last')
          , deactivate = $lastActiveToken && this.$firstActiveToken ? $lastActiveToken.index() > this.$firstActiveToken.index() : false

        if (deactivate) return this.deactivate( $lastActiveToken )
      }

      var $firstActiveToken = this.$wrapper.find('.active:first')
        , $prevToken = $firstActiveToken.prevAll('.token:first')

      if (!$prevToken.length) {
        $prevToken = this.$wrapper.find('.token:first')
      }

      if (!$prevToken.length && !add) {
        this.$input.focus()
        return
      }

      this.activate( $prevToken, add )
    }

  , activate: function ($token, add, multi, remember) {

      if (!$token) return

      if (typeof remember === 'undefined') var remember = true

      if (multi) var add = true

      this.$copyHelper.focus()

      if (!add) {
        this.$wrapper.find('.active').removeClass('active')
        if (remember) {
          this.$firstActiveToken = $token
        } else {
          delete this.$firstActiveToken
        }
      }

      if (multi && this.$firstActiveToken) {
        // Determine first active token and the current tokens indicies
        // Account for the 1 hidden textarea by subtracting 1 from both
        var i = this.$firstActiveToken.index() - 2
          , a = $token.index() - 2
          , _self = this

        this.$wrapper.find('.token').slice( Math.min(i, a) + 1, Math.max(i, a) ).each( function() {
          _self.activate( $(this), true )
        })
      }

      $token.addClass('active')
      this.$copyHelper.val( this.getTokensList( null, null, true ) ).select()
    }

  , activateAll: function() {
      var _self = this

      this.$wrapper.find('.token').each( function (i) {
        _self.activate($(this), i !== 0, false, false)
      })
    }

  , deactivate: function($token) {
      if (!$token) return

      $token.removeClass('active')
      this.$copyHelper.val( this.getTokensList( null, null, true ) ).select()
    }

  , toggle: function($token) {
      if (!$token) return

      $token.toggleClass('active')
      this.$copyHelper.val( this.getTokensList( null, null, true ) ).select()
    }

  , edit: function ($token) {
      if (!$token) return

      var attrs = $token.data('attrs')

      // Allow changing input value before editing
      var options = { attrs: attrs, relatedTarget: $token.get(0) }
      var editEvent = $.Event('tokenfield:edittoken', options)
      this.$element.trigger( editEvent )

      // Edit event can be cancelled if default is prevented
      if (editEvent.isDefaultPrevented()) return

      $token.find('.token-label').text(attrs.value)
      var tokenWidth = $token.outerWidth()

      var $_input = this.$input.hasClass('tt-input') ? this.$input.parent() : this.$input

      $token.replaceWith( $_input )

      this.preventCreateTokens = true

      this.$input.val( attrs.value )
                .select()
                .data( 'edit', true )
                //.width( tokenWidth )

      this.update();

      // Indicate that token is now being edited, and is replaced with an input field in the DOM
      this.$element.trigger($.Event('tokenfield:editedtoken', options ))
    }

  , unedit: function (focus) {
      var $_input = this.$input.hasClass('tt-input') ? this.$input.parent() : this.$input
      $_input.appendTo( this.$wrapper )

      this.$input.data('edit', false)
      this.$mirror.text('')

      this.update()

      // Because moving the input element around in DOM
      // will cause it to lose focus, we provide an option
      // to re-focus the input after appending it to the wrapper
      if (focus) {
        var _self = this
        setTimeout(function () {
          _self.$input.focus()
        }, 1)
      }
    }

  , remove: function (e, direction) {
      if (this.$input.is(document.activeElement) || this._disabled || this._readonly) return

      var $token = (e.type === 'click') ? $(e.target).closest('.token') : this.$wrapper.find('.token.active')

      if (e.type !== 'click') {
        if (!direction) var direction = 'prev'
        this[direction]()

        // Was it the first token?
        if (direction === 'prev') var firstToken = $token.first().prevAll('.token:first').length === 0
      }

      // Prepare events and their options
      var options = { attrs: this.getTokenData( $token ), relatedTarget: $token.get(0) }
        , removeEvent = $.Event('tokenfield:removetoken', options)

      this.$element.trigger(removeEvent);

      // Remove event can be intercepted and cancelled
      if (removeEvent.isDefaultPrevented()) return

      var removedEvent = $.Event('tokenfield:removedtoken', options)
        , changeEvent = $.Event('change', { initiator: 'tokenfield' })

      // Remove token from DOM
      $token.remove()

      // Trigger events
      this.$element.val( this.getTokensList() ).trigger( removedEvent ).trigger( changeEvent )

      // Focus, when necessary:
      // When there are no more tokens, or if this was the first token
      // and it was removed with backspace or it was clicked on
      if (!this.$wrapper.find('.token').length || e.type === 'click' || firstToken) this.$input.focus()

      // Adjust input width
      //this.$input.css('width', this.options.minWidth + 'px')
      this.update()

      // Cancel original event handlers
      e.preventDefault()
      e.stopPropagation()
    }

    /**
     * Update tokenfield dimensions
     */
  , update: function (e) {
      var value = this.$input.val()
        , inputPaddingLeft = parseInt(this.$input.css('padding-left'), 10)
        , inputPaddingRight = parseInt(this.$input.css('padding-right'), 10)
        , inputPadding = inputPaddingLeft + inputPaddingRight

      if (this.$input.data('edit')) {

        if (!value) {
          value = this.$input.prop("placeholder")
        }
        if (value === this.$mirror.text()) return

        this.$mirror.text(value)

        var mirrorWidth = this.$mirror.width() + 10;
        if ( mirrorWidth > this.$wrapper.width() ) {
          return this.$input.width( /*this.$wrapper.width()*/ )
        }

        //this.$input.width( mirrorWidth )
      }
      else {
        //temporary reset width to minimal value to get proper results
        //this.$input.width(this.options.minWidth);
        
        var w = (this.textDirection === 'rtl')
              ? this.$input.offset().left + this.$input.outerWidth() - this.$wrapper.offset().left - parseInt(this.$wrapper.css('padding-left'), 10) - inputPadding - 1
              : this.$wrapper.offset().left + this.$wrapper.width() + parseInt(this.$wrapper.css('padding-left'), 10) - this.$input.offset().left - inputPadding;
        //
        // some usecases pre-render widget before attaching to DOM,
        // dimensions returned by jquery will be NaN -> we default to 100%
        // so placeholder won't be cut off.
        //isNaN(w) ? this.$input.width('100%') : this.$input.width(w);
      }
    }

  , focusInput: function (e) {
      if ( $(e.target).closest('.token').length || $(e.target).closest('.token-input').length || $(e.target).closest('.tt-dropdown-menu').length ) return
      // Focus only after the current call stack has cleared,
      // otherwise has no effect.
      // Reason: mousedown is too early - input will lose focus
      // after mousedown. However, since the input may be moved
      // in DOM, there may be no click or mouseup event triggered.
      var _self = this
      setTimeout(function() {
        _self.$input.focus()
      }, 0)
    }

  , search: function () {
      if ( this.$input.data('ui-autocomplete') ) {
        this.$input.autocomplete('search')
      }
    }

  , disable: function () {
      this.setProperty('disabled', true);
    }

  , enable: function () {
      this.setProperty('disabled', false);
    }

  , readonly: function () {
      this.setProperty('readonly', true);
    }

  , writeable: function () {
      this.setProperty('readonly', false);
    }

  , setProperty: function(property, value) {
      this['_' + property] = value;
      this.$input.prop(property, value);
      this.$element.prop(property, value);
      this.$wrapper[ value ? 'addClass' : 'removeClass' ](property);
  }

  , destroy: function() {
      // Set field value
      this.$element.val( this.getTokensList() );
      // Restore styles and properties
      this.$element.css( this.$element.data('original-styles') );
      this.$element.prop( 'tabindex', this.$element.data('original-tabindex') );

      // Re-route tokenfield label to original input
      var $label = $( 'label[for="' + this.$input.prop('id') + '"]' )
      if ( $label.length ) {
        $label.prop( 'for', this.$element.prop('id') )
      }

      // Move original element outside of tokenfield wrapper
      this.$element.insertBefore( this.$wrapper );

      // Remove tokenfield-related data
      this.$element.removeData('original-styles')
                   .removeData('original-tabindex')
                   .removeData('bs.tokenfield');

      // Remove tokenfield from DOM
      this.$wrapper.remove();
      this.$mirror.remove();

      var $_element = this.$element;

      return $_element;
  }

  }


 /* TOKENFIELD PLUGIN DEFINITION
  * ======================== */

  var old = $.fn.tokenfield

  $.fn.tokenfield = function (option, param) {
    var value
      , args = []

    Array.prototype.push.apply( args, arguments );

    var elements = this.each(function () {
      var $this = $(this)
        , data = $this.data('bs.tokenfield')
        , options = typeof option == 'object' && option

      if (typeof option === 'string' && data && data[option]) {
        args.shift()
        value = data[option].apply(data, args)
      } else {
        if (!data && typeof option !== 'string' && !param) {
          $this.data('bs.tokenfield', (data = new Tokenfield(this, options)))
          $this.trigger('tokenfield:initialize')
        }
      }
    })

    return typeof value !== 'undefined' ? value : elements;
  }

  $.fn.tokenfield.defaults = {
    minWidth: 60,
    minLength: 0,
    html: true,
    allowEditing: true,
    allowPasting: true,
    limit: 0,
    autocomplete: {},
    typeahead: {},
    showAutocompleteOnFocus: false,
    createTokensOnBlur: false,
    delimiter: ',',
    beautify: true,
    inputType: 'text'
  }

  $.fn.tokenfield.Constructor = Tokenfield


 /* TOKENFIELD NO CONFLICT
  * ================== */

  $.fn.tokenfield.noConflict = function () {
    $.fn.tokenfield = old
    return this
  }

  return Tokenfield;

}));
/*
     _ _      _       _
 ___| (_) ___| | __  (_)___
/ __| | |/ __| |/ /  | / __|
\__ \ | | (__|   < _ | \__ \
|___/_|_|\___|_|\_(_)/ |___/
                   |__/

 Version: 1.6.0
  Author: Ken Wheeler
 Website: http://kenwheeler.github.io
    Docs: http://kenwheeler.github.io/slick
    Repo: http://github.com/kenwheeler/slick
  Issues: http://github.com/kenwheeler/slick/issues

 */
(function($) {
    'use strict';
    var Slick = window.a4jSlick || {};

    Slick = (function() {

        var instanceUid = 0;

        function Slick(element, settings) {

            var _ = this, dataSettings;

            _.defaults = {
                accessibility: true,
                adaptiveHeight: false,
                appendArrows: $(element),
                appendDots: $(element),
                arrows: true,
                asNavFor: null,
                prevArrow: '<span data-role="none" class="a4j-slick-prev" aria-label="Previous" tabindex="0" role="button"><i class="ing-icon-angle-left ing-fw"></i></span>',
                nextArrow: '<span data-role="none" class="a4j-slick-next" aria-label="Next" tabindex="0" role="button"><i class="ing-icon-angle-right ing-fw"></i></span>',
                autoplay: false,
                autoplaySpeed: 3000,
                centerMode: false,
                centerPadding: '50px',
                cssEase: 'ease',
                customPaging: function(slider, i) {
                    return $('<i data-role="none" role="button" tabindex="0" />').text(i + 1);
                },
                dots: false,
                dotsClass: 'a4j-slick-dots',
                draggable: true,
                easing: 'linear',
                edgeFriction: 0.35,
                fade: false,
                focusOnSelect: false,
                infinite: false,
                initialSlide: 0,
                lazyLoad: 'ondemand',
                mobileFirst: false,
                pauseOnHover: true,
                pauseOnFocus: true,
                pauseOnDotsHover: false,
                respondTo: 'window',
                responsive: null,
                rows: 1,
                rtl: false,
                slide: '',
                slidesPerRow: 1,
                slidesToShow: 1,
                slidesToScroll: 1,
                speed: 500,
                swipe: true,
                swipeToSlide: false,
                touchMove: true,
                touchThreshold: 5,
                useCSS: true,
                useTransform: true,
                variableWidth: false,
                vertical: false,
                verticalSwiping: false,
                waitForAnimate: true,
                zIndex: 1000
            };

            _.initials = {
                animating: false,
                dragging: false,
                autoPlayTimer: null,
                currentDirection: 0,
                currentLeft: null,
                currentSlide: 0,
                direction: 1,
                $dots: null,
                listWidth: null,
                listHeight: null,
                loadIndex: 0,
                $nextArrow: null,
                $prevArrow: null,
                slideCount: null,
                slideWidth: null,
                $slideTrack: null,
                $slides: null,
                sliding: false,
                slideOffset: 0,
                swipeLeft: null,
                $list: null,
                touchObject: {},
                transformsEnabled: false,
                unslicked: false
            };

            $.extend(_, _.initials);

            _.activeBreakpoint = null;
            _.animType = null;
            _.animProp = null;
            _.breakpoints = [];
            _.breakpointSettings = [];
            _.cssTransitions = false;
            _.focussed = false;
            _.interrupted = false;
            _.hidden = 'hidden';
            _.paused = true;
            _.positionProp = null;
            _.respondTo = null;
            _.rowCount = 1;
            _.shouldClick = true;
            _.$slider = $(element);
            _.$slidesCache = null;
            _.transformType = null;
            _.transitionType = null;
            _.visibilityChange = 'visibilitychange';
            _.windowWidth = 0;
            _.windowTimer = null;

            dataSettings = JSON.parse($(element).attr('data-slick') || '{}');

            _.options = $.extend({}, _.defaults, settings, dataSettings);

            _.currentSlide = _.options.initialSlide;

            _.originalSettings = _.options;

            if (typeof document.mozHidden !== 'undefined') {
                _.hidden = 'mozHidden';
                _.visibilityChange = 'mozvisibilitychange';
            } else if (typeof document.webkitHidden !== 'undefined') {
                _.hidden = 'webkitHidden';
                _.visibilityChange = 'webkitvisibilitychange';
            }

            _.autoPlay = $.proxy(_.autoPlay, _);
            _.autoPlayClear = $.proxy(_.autoPlayClear, _);
            _.autoPlayIterator = $.proxy(_.autoPlayIterator, _);
            _.changeSlide = $.proxy(_.changeSlide, _);
            _.clickHandler = $.proxy(_.clickHandler, _);
            _.selectHandler = $.proxy(_.selectHandler, _);
            _.setPosition = $.proxy(_.setPosition, _);
            _.swipeHandler = $.proxy(_.swipeHandler, _);
            _.dragHandler = $.proxy(_.dragHandler, _);
            _.keyHandler = $.proxy(_.keyHandler, _);

            _.instanceUid = instanceUid++;

            // A simple way to check for HTML strings
            // Strict HTML recognition (must start with <)
            // Extracted from jQuery v1.11 source
            _.htmlExpr = /^(?:\s*(<[\w\W]+>)[^>]*)$/;


            _.registerBreakpoints();
            _.init(true);
			
			$(element).data('ingslick',_);
        }

        return Slick;

    }());

    Slick.prototype.activateADA = function() {
        var _ = this;

        _.$slideTrack.find('.a4j-slick-active').attr({
            'aria-hidden': 'false'
        }).find('a, input, button, select').attr({
            'tabindex': '0'
        });

    };

    Slick.prototype.addSlide = Slick.prototype.slickAdd = function(markup, index, addBefore) {
		var _ = this;

        if (typeof(index) === 'boolean') {
            addBefore = index;
            index = null;
        } else if (index < 0 || (index >= _.slideCount)) {
            return false;
        }

        _.unload();

        if (typeof(index) === 'number') {
            if (index === 0 && _.$slides.length === 0) {
                _.getRowsItems($(markup)).appendTo(_.$slideTrack);
            } else if (addBefore) {
                _.getRowsItems($(markup)).insertBefore(_.$slides.eq(index));
            } else {
                _.getRowsItems($(markup)).insertAfter(_.$slides.eq(index));
            }
        } else {
            if (addBefore === true) {
                _.getRowsItems($(markup)).prependTo(_.$slideTrack);
            } else {
                _.getRowsItems($(markup)).appendTo(_.$slideTrack);
            }
        }

        _.$slides = _.$slideTrack.children(this.options.slide);

        _.$slideTrack.children(this.options.slide).detach();

        _.$slideTrack.append(_.$slides);

        _.$slides.each(function(index, element) {
            $(element).attr('data-slick-index', index);
        });

        _.$slidesCache = _.$slides;

        _.reinit();

    };
	
	

    Slick.prototype.animateHeight = function() {
        var _ = this;
        if (_.options.slidesToShow === 1 && _.options.adaptiveHeight === true && _.options.vertical === false) {
            var targetHeight = _.$slides.eq(_.currentSlide).outerHeight(true);
            _.$list.animate({
                height: targetHeight
            }, _.options.speed);
        }
    };

    Slick.prototype.animateSlide = function(targetLeft, callback) {

        var animProps = {},
            _ = this;

        _.animateHeight();

        if (_.options.rtl === true && _.options.vertical === false) {
            targetLeft = -targetLeft;
        }
        if (_.transformsEnabled === false) {
            if (_.options.vertical === false) {
                _.$slideTrack.animate({
                    left: targetLeft
                }, _.options.speed, _.options.easing, callback);
            } else {
                _.$slideTrack.animate({
                    top: targetLeft
                }, _.options.speed, _.options.easing, callback);
            }

        } else {

            if (_.cssTransitions === false) {
                if (_.options.rtl === true) {
                    _.currentLeft = -(_.currentLeft);
                }
                $({
                    animStart: _.currentLeft
                }).animate({
                    animStart: targetLeft
                }, {
                    duration: _.options.speed,
                    easing: _.options.easing,
                    step: function(now) {
                        now = Math.ceil(now);
                        if (_.options.vertical === false) {
                            animProps[_.animType] = 'translate(' +
                                now + 'px, 0px)';
                            _.$slideTrack.css(animProps);
                        } else {
                            animProps[_.animType] = 'translate(0px,' +
                                now + 'px)';
                            _.$slideTrack.css(animProps);
                        }
                    },
                    complete: function() {
                        if (callback) {
                            callback.call();
                        }
                    }
                });

            } else {

                _.applyTransition();
                targetLeft = Math.ceil(targetLeft);

                if (_.options.vertical === false) {
                    animProps[_.animType] = 'translate3d(' + targetLeft + 'px, 0px, 0px)';
                } else {
                    animProps[_.animType] = 'translate3d(0px,' + targetLeft + 'px, 0px)';
                }
                _.$slideTrack.css(animProps);

                if (callback) {
                    setTimeout(function() {

                        _.disableTransition();

                        callback.call();
                    }, _.options.speed);
                }

            }

        }

    };

    Slick.prototype.getNavTarget = function() {

        var _ = this,
            asNavFor = _.options.asNavFor;

        if ( asNavFor && asNavFor !== null ) {
            asNavFor = $(asNavFor).not(_.$slider);
        }

        return asNavFor;

    };

    Slick.prototype.asNavFor = function(index) {

        var _ = this,
            asNavFor = _.getNavTarget();

        if ( asNavFor !== null && typeof asNavFor === 'object' ) {
            asNavFor.each(function() {
                var target = $(this).a4jslick('getSlick');
                if(!target.unslicked) {
                    target.slideHandler(index, true);
                }
            });
        }

    };

    Slick.prototype.applyTransition = function(slide) {

        var _ = this,
            transition = {};

        if (_.options.fade === false) {
            transition[_.transitionType] = _.transformType + ' ' + _.options.speed + 'ms ' + _.options.cssEase;
        } else {
            transition[_.transitionType] = 'opacity ' + _.options.speed + 'ms ' + _.options.cssEase;
        }

        if (_.options.fade === false) {
            _.$slideTrack.css(transition);
        } else {
            _.$slides.eq(slide).css(transition);
        }

    };

    Slick.prototype.autoPlay = function() {

        var _ = this;

        _.autoPlayClear();

        if ( _.slideCount > _.options.slidesToShow ) {
            _.autoPlayTimer = setInterval( _.autoPlayIterator, _.options.autoplaySpeed );
        }

    };

    Slick.prototype.autoPlayClear = function() {

        var _ = this;

        if (_.autoPlayTimer) {
            clearInterval(_.autoPlayTimer);
        }

    };

    Slick.prototype.autoPlayIterator = function() {

        var _ = this,
            slideTo = _.currentSlide + _.options.slidesToScroll;

        if ( !_.paused && !_.interrupted && !_.focussed ) {

            if ( _.options.infinite === false ) {

                if ( _.direction === 1 && ( _.currentSlide + 1 ) === ( _.slideCount - 1 )) {
                    _.direction = 0;
                }

                else if ( _.direction === 0 ) {

                    slideTo = _.currentSlide - _.options.slidesToScroll;

                    if ( _.currentSlide - 1 === 0 ) {
                        _.direction = 1;
                    }

                }

            }

            _.slideHandler( slideTo );

        }

    };

    Slick.prototype.buildArrows = function() {

        var _ = this;

        if (_.options.arrows === true ) {

            _.$prevArrow = $(_.options.prevArrow).addClass('a4j-slick-arrow');
            _.$nextArrow = $(_.options.nextArrow).addClass('a4j-slick-arrow');

            if( _.slideCount > _.options.slidesToShow ) {

                _.$prevArrow.removeClass('a4j-slick-hidden').removeAttr('aria-hidden tabindex');
                _.$nextArrow.removeClass('a4j-slick-hidden').removeAttr('aria-hidden tabindex');

                if (_.htmlExpr.test(_.options.prevArrow)) {
                    _.$prevArrow.appendTo(_.options.appendArrows);
                }

                if (_.htmlExpr.test(_.options.nextArrow)) {
                    _.$nextArrow.appendTo(_.options.appendArrows);
                }

                if (_.options.infinite !== true) {
                    _.$prevArrow
                        .addClass('a4j-slick-disabled')
                        .attr('aria-disabled', 'true');
                }

            } else {

                _.$prevArrow.add( _.$nextArrow )

                    .addClass('a4j-slick-hidden')
                    .attr({
                        'aria-disabled': 'true',
                        'tabindex': '-1'
                    });

            }

        }

    };

    Slick.prototype.buildDots = function() {

        var _ = this,
            i, dot;

        if (_.options.dots === true && _.slideCount > _.options.slidesToShow) {

            _.$slider.addClass('a4j-slick-dotted');

            dot = $('<div />').addClass(_.options.dotsClass);

            for (i = 0; i <= _.getDotCount(); i += 1) {
                dot.append(_.options.customPaging.call(this, _, i));
            }

            _.$dots = dot.appendTo(_.options.appendDots);

            _.$dots.find('i').first().addClass('a4j-slick-active').attr('aria-hidden', 'false');

        }

    };

    Slick.prototype.buildOut = function() {

        var _ = this;

        _.$slides =
            _.$slider
                .children( _.options.slide + ':not(.a4j-slick-cloned)')
                .addClass('a4j-slick-slide');

        _.slideCount = _.$slides.length;

        _.$slides.each(function(index, element) {
            $(element)
                .attr('data-slick-index', index)
                .data('originalStyling', $(element).attr('style') || '');
        });

        _.$slider.addClass('a4j-slick-slider');
        _.$slider.addClass('a4j-slick-slider');

        _.$slideTrack = (_.slideCount === 0) ?
            $('<div class="a4j-slick-track"/>').appendTo(_.$slider) :
            _.$slides.wrapAll('<div class="a4j-slick-track"/>').parent();

        _.$list = _.$slideTrack.wrap(
            '<div aria-live="polite" class="a4j-slick-list"/>').parent();
        _.$slideTrack.css('opacity', 0);

        if (_.options.centerMode === true || _.options.swipeToSlide === true) {
            _.options.slidesToScroll = 1;
        }

        $('img[data-lazy]', _.$slider).not('[src]').addClass('a4j-slick-loading');

        _.setupInfinite();

        _.buildArrows();

        _.buildDots();

        _.updateDots();


        _.setSlideClasses(typeof _.currentSlide === 'number' ? _.currentSlide : 0);

        if (_.options.draggable === true) {
            _.$list.addClass('draggable');
        }

    };

    Slick.prototype.buildRows = function() {

        var _ = this, a, b, c, newSlides, numOfSlides, originalSlides,slidesPerSection;

        newSlides = document.createDocumentFragment();
        originalSlides = _.$slider.children();

        if(_.options.rows > 1) {

            slidesPerSection = _.options.slidesPerRow * _.options.rows;
            numOfSlides = Math.ceil(
                originalSlides.length / slidesPerSection
            );

            for(a = 0; a < numOfSlides; a++){
                var slide = document.createElement('div');
                for(b = 0; b < _.options.rows; b++) {
                    var row = document.createElement('div');
					row.className = "ingallery-row";
                    for(c = 0; c < _.options.slidesPerRow; c++) {
                        var target = (a * slidesPerSection + ((b * _.options.slidesPerRow) + c));
                        if (originalSlides.get(target)) {
                            row.appendChild(originalSlides.get(target));
                        }
                    }
                    slide.appendChild(row);
                }
                newSlides.appendChild(slide);
            }

            _.$slider.empty().append(newSlides);
            _.$slider.children().children().children()
                .css({
                    'width':(100 / _.options.slidesPerRow) + '%',
                });

        }

    };
	
    Slick.prototype.getRowsItems = function(_jqItems){
		var _ = this;
		if(_.options.rows <= 1){
			return _jqItems;
		}
		var slide = $('<div></div>');
		var index = 0;
		for(var a=0;a<_.options.rows;a++) {
			var row = $('<div class="ingallery-row"></div>');
			slide.append(row);
			for(var b=0;b<_.options.slidesPerRow;b++){
				var _item = $(_jqItems.get(index)).css('width',(100 / _.options.slidesPerRow) + '%');
				row.append(_item);
				index++;
			}
		}
        return slide;
    };
	
    Slick.prototype.checkResponsive = function(initial, forceUpdate) {

        var _ = this,
            breakpoint, targetBreakpoint, respondToWidth, triggerBreakpoint = false;
        var sliderWidth = _.$slider.width();
        var windowWidth = window.innerWidth || $(window).width();

        if (_.respondTo === 'window') {
            respondToWidth = windowWidth;
        } else if (_.respondTo === 'slider') {
            respondToWidth = sliderWidth;
        } else if (_.respondTo === 'min') {
            respondToWidth = Math.min(windowWidth, sliderWidth);
        }

        if ( _.options.responsive &&
            _.options.responsive.length &&
            _.options.responsive !== null) {

            targetBreakpoint = null;

            for (breakpoint in _.breakpoints) {
                if (_.breakpoints.hasOwnProperty(breakpoint)) {
                    if (_.originalSettings.mobileFirst === false) {
                        if (respondToWidth < _.breakpoints[breakpoint]) {
                            targetBreakpoint = _.breakpoints[breakpoint];
                        }
                    } else {
                        if (respondToWidth > _.breakpoints[breakpoint]) {
                            targetBreakpoint = _.breakpoints[breakpoint];
                        }
                    }
                }
            }

            if (targetBreakpoint !== null) {
                if (_.activeBreakpoint !== null) {
                    if (targetBreakpoint !== _.activeBreakpoint || forceUpdate) {
                        _.activeBreakpoint =
                            targetBreakpoint;
                        if (_.breakpointSettings[targetBreakpoint] === 'unslick') {
                            _.unslick(targetBreakpoint);
                        } else {
                            _.options = $.extend({}, _.originalSettings,
                                _.breakpointSettings[
                                    targetBreakpoint]);
                            if (initial === true) {
                                _.currentSlide = _.options.initialSlide;
                            }
                            _.refresh(initial);
                        }
                        triggerBreakpoint = targetBreakpoint;
                    }
                } else {
                    _.activeBreakpoint = targetBreakpoint;
                    if (_.breakpointSettings[targetBreakpoint] === 'unslick') {
                        _.unslick(targetBreakpoint);
                    } else {
                        _.options = $.extend({}, _.originalSettings,
                            _.breakpointSettings[
                                targetBreakpoint]);
                        if (initial === true) {
                            _.currentSlide = _.options.initialSlide;
                        }
                        _.refresh(initial);
                    }
                    triggerBreakpoint = targetBreakpoint;
                }
            } else {
                if (_.activeBreakpoint !== null) {
                    _.activeBreakpoint = null;
                    _.options = _.originalSettings;
                    if (initial === true) {
                        _.currentSlide = _.options.initialSlide;
                    }
                    _.refresh(initial);
                    triggerBreakpoint = targetBreakpoint;
                }
            }

            // only trigger breakpoints during an actual break. not on initialize.
            if( !initial && triggerBreakpoint !== false ) {
                _.$slider.trigger('breakpoint', [_, triggerBreakpoint]);
            }
        }

    };

    Slick.prototype.changeSlide = function(event, dontAnimate) {

        var _ = this,
            $target = $(event.currentTarget),
            indexOffset, slideOffset, unevenOffset;

        // If target is a link, prevent default action.
        if($target.is('a')) {
            event.preventDefault();
        }

        // If target is not the <li> element (ie: a child), find the <li>.
        if(!$target.is('i')) {
            $target = $target.closest('i');
        }

        unevenOffset = (_.slideCount % _.options.slidesToScroll !== 0);
        indexOffset = unevenOffset ? 0 : (_.slideCount - _.currentSlide) % _.options.slidesToScroll;

        switch (event.data.message) {

            case 'previous':
                slideOffset = indexOffset === 0 ? _.options.slidesToScroll : _.options.slidesToShow - indexOffset;
                if (_.slideCount > _.options.slidesToShow) {
                    _.slideHandler(_.currentSlide - slideOffset, false, dontAnimate);
                }
                break;

            case 'next':
                slideOffset = indexOffset === 0 ? _.options.slidesToScroll : indexOffset;
                if (_.slideCount > _.options.slidesToShow) {
                    _.slideHandler(_.currentSlide + slideOffset, false, dontAnimate);
                }
                break;

            case 'index':
                var index = event.data.index === 0 ? 0 :
                    event.data.index || $target.index() * _.options.slidesToScroll;

                _.slideHandler(_.checkNavigable(index), false, dontAnimate);
                $target.children().trigger('focus');
                break;

            default:
                return;
        }

    };

    Slick.prototype.checkNavigable = function(index) {

        var _ = this,
            navigables, prevNavigable;

        navigables = _.getNavigableIndexes();
        prevNavigable = 0;
        if (index > navigables[navigables.length - 1]) {
            index = navigables[navigables.length - 1];
        } else {
            for (var n in navigables) {
                if (index < navigables[n]) {
                    index = prevNavigable;
                    break;
                }
                prevNavigable = navigables[n];
            }
        }

        return index;
    };

    Slick.prototype.cleanUpEvents = function() {

        var _ = this;

        if (_.options.dots && _.$dots !== null) {

            $('i', _.$dots)
                .off('click.slick', _.changeSlide)
                .off('mouseenter.slick', $.proxy(_.interrupt, _, true))
                .off('mouseleave.slick', $.proxy(_.interrupt, _, false));

        }

        _.$slider.off('focus.slick blur.slick');

        if (_.options.arrows === true && _.slideCount > _.options.slidesToShow) {
            _.$prevArrow && _.$prevArrow.off('click.slick', _.changeSlide);
            _.$nextArrow && _.$nextArrow.off('click.slick', _.changeSlide);
        }

        _.$list.off('touchstart.slick mousedown.slick', _.swipeHandler);
        _.$list.off('touchmove.slick mousemove.slick', _.swipeHandler);
        _.$list.off('touchend.slick mouseup.slick', _.swipeHandler);
        _.$list.off('touchcancel.slick mouseleave.slick', _.swipeHandler);

        _.$list.off('click.slick', _.clickHandler);

        $(document).off(_.visibilityChange, _.visibility);

        _.cleanUpSlideEvents();

        if (_.options.accessibility === true) {
            _.$list.off('keydown.slick', _.keyHandler);
        }

        if (_.options.focusOnSelect === true) {
            $(_.$slideTrack).children().off('click.slick', _.selectHandler);
        }

        $(window).off('orientationchange.slick.slick-' + _.instanceUid, _.orientationChange);

        $(window).off('resize.slick.slick-' + _.instanceUid, _.resize);

        $('[draggable!=true]', _.$slideTrack).off('dragstart', _.preventDefault);

        $(window).off('load.slick.slick-' + _.instanceUid, _.setPosition);
        $(document).off('ready.slick.slick-' + _.instanceUid, _.setPosition);

    };

    Slick.prototype.cleanUpSlideEvents = function() {

        var _ = this;

        _.$list.off('mouseenter.slick', $.proxy(_.interrupt, _, true));
        _.$list.off('mouseleave.slick', $.proxy(_.interrupt, _, false));

    };

    Slick.prototype.cleanUpRows = function() {

        var _ = this, originalSlides;

        if(_.options.rows > 1) {
            originalSlides = _.$slides.children().children();
            originalSlides.removeAttr('style');
            _.$slider.empty().append(originalSlides);
        }

    };

    Slick.prototype.clickHandler = function(event) {

        var _ = this;

        if (_.shouldClick === false) {
            event.stopImmediatePropagation();
            event.stopPropagation();
            event.preventDefault();
        }

    };

    Slick.prototype.destroy = function(refresh) {

        var _ = this;

        _.autoPlayClear();

        _.touchObject = {};

        _.cleanUpEvents();

        $('.a4j-slick-cloned', _.$slider).detach();

        if (_.$dots) {
            _.$dots.remove();
        }


        if ( _.$prevArrow && _.$prevArrow.length ) {

            _.$prevArrow
                .removeClass('a4j-slick-disabled a4j-slick-arrow a4j-slick-hidden')
                .removeAttr('aria-hidden aria-disabled tabindex')
                .css('display','');

            if ( _.htmlExpr.test( _.options.prevArrow )) {
                _.$prevArrow.remove();
            }
        }

        if ( _.$nextArrow && _.$nextArrow.length ) {

            _.$nextArrow
                .removeClass('a4j-slick-disabled a4j-slick-arrow a4j-slick-hidden')
                .removeAttr('aria-hidden aria-disabled tabindex')
                .css('display','');

            if ( _.htmlExpr.test( _.options.nextArrow )) {
                _.$nextArrow.remove();
            }

        }


        if (_.$slides) {

            _.$slides
                .removeClass('a4j-slick-slide a4j-slick-active a4j-slick-center a4j-slick-visible a4j-slick-current')
                .removeAttr('aria-hidden')
                .removeAttr('data-slick-index')
                .each(function(){
                    $(this).attr('style', $(this).data('originalStyling'));
                });

            _.$slideTrack.children(this.options.slide).detach();

            _.$slideTrack.detach();

            _.$list.detach();

            _.$slider.append(_.$slides);
        }

        _.cleanUpRows();

        _.$slider.removeClass('a4j-slick-slider');
        _.$slider.removeClass('a4j-slick-initialized');
        _.$slider.removeClass('a4j-slick-dotted');

        _.unslicked = true;

        if(!refresh) {
            _.$slider.trigger('destroy', [_]);
        }

    };

    Slick.prototype.disableTransition = function(slide) {

        var _ = this,
            transition = {};

        transition[_.transitionType] = '';

        if (_.options.fade === false) {
            _.$slideTrack.css(transition);
        } else {
            _.$slides.eq(slide).css(transition);
        }

    };

    Slick.prototype.fadeSlide = function(slideIndex, callback) {

        var _ = this;

        if (_.cssTransitions === false) {

            _.$slides.eq(slideIndex).css({
                zIndex: _.options.zIndex
            });

            _.$slides.eq(slideIndex).animate({
                opacity: 1
            }, _.options.speed, _.options.easing, callback);

        } else {

            _.applyTransition(slideIndex);

            _.$slides.eq(slideIndex).css({
                opacity: 1,
                zIndex: _.options.zIndex
            });

            if (callback) {
                setTimeout(function() {

                    _.disableTransition(slideIndex);

                    callback.call();
                }, _.options.speed);
            }

        }

    };

    Slick.prototype.fadeSlideOut = function(slideIndex) {

        var _ = this;

        if (_.cssTransitions === false) {

            _.$slides.eq(slideIndex).animate({
                opacity: 0,
                zIndex: _.options.zIndex - 2
            }, _.options.speed, _.options.easing);

        } else {

            _.applyTransition(slideIndex);

            _.$slides.eq(slideIndex).css({
                opacity: 0,
                zIndex: _.options.zIndex - 2
            });

        }

    };

    Slick.prototype.filterSlides = Slick.prototype.slickFilter = function(filter) {

        var _ = this;

        if (filter !== null) {

            _.$slidesCache = _.$slides;

            _.unload();

            _.$slideTrack.children(this.options.slide).detach();

            _.$slidesCache.filter(filter).appendTo(_.$slideTrack);

            _.reinit();

        }

    };

    Slick.prototype.focusHandler = function() {

        var _ = this;

        _.$slider
            .off('focus.slick blur.slick')
            .on('focus.slick blur.slick',
                '*:not(.a4j-slick-arrow)', function(event) {

            event.stopImmediatePropagation();
            var $sf = $(this);

            setTimeout(function() {

                if( _.options.pauseOnFocus ) {
                    _.focussed = $sf.is(':focus');
                    _.autoPlay();
                }

            }, 0);

        });
    };

    Slick.prototype.getCurrent = Slick.prototype.slickCurrentSlide = function() {

        var _ = this;
        return _.currentSlide;

    };

    Slick.prototype.getDotCount = function() {

        var _ = this;

        var breakPoint = 0;
        var counter = 0;
        var pagerQty = 0;

        if (_.options.infinite === true) {
            while (breakPoint < _.slideCount) {
                ++pagerQty;
                breakPoint = counter + _.options.slidesToScroll;
                counter += _.options.slidesToScroll <= _.options.slidesToShow ? _.options.slidesToScroll : _.options.slidesToShow;
            }
        } else if (_.options.centerMode === true) {
            pagerQty = _.slideCount;
        } else if(!_.options.asNavFor) {
            pagerQty = 1 + Math.ceil((_.slideCount - _.options.slidesToShow) / _.options.slidesToScroll);
        }else {
            while (breakPoint < _.slideCount) {
                ++pagerQty;
                breakPoint = counter + _.options.slidesToScroll;
                counter += _.options.slidesToScroll <= _.options.slidesToShow ? _.options.slidesToScroll : _.options.slidesToShow;
            }
        }

        return pagerQty - 1;

    };

    Slick.prototype.getLeft = function(slideIndex) {

        var _ = this,
            targetLeft,
            verticalHeight,
            verticalOffset = 0,
            targetSlide;

        _.slideOffset = 0;
        verticalHeight = _.$slides.first().outerHeight(true);

        if (_.options.infinite === true) {
            if (_.slideCount > _.options.slidesToShow) {
                _.slideOffset = (_.slideWidth * _.options.slidesToShow) * -1;
                verticalOffset = (verticalHeight * _.options.slidesToShow) * -1;
            }
            if (_.slideCount % _.options.slidesToScroll !== 0) {
                if (slideIndex + _.options.slidesToScroll > _.slideCount && _.slideCount > _.options.slidesToShow) {
                    if (slideIndex > _.slideCount) {
                        _.slideOffset = ((_.options.slidesToShow - (slideIndex - _.slideCount)) * _.slideWidth) * -1;
                        verticalOffset = ((_.options.slidesToShow - (slideIndex - _.slideCount)) * verticalHeight) * -1;
                    } else {
                        _.slideOffset = ((_.slideCount % _.options.slidesToScroll) * _.slideWidth) * -1;
                        verticalOffset = ((_.slideCount % _.options.slidesToScroll) * verticalHeight) * -1;
                    }
                }
            }
        } else {
            if (slideIndex + _.options.slidesToShow > _.slideCount) {
                _.slideOffset = ((slideIndex + _.options.slidesToShow) - _.slideCount) * _.slideWidth;
                verticalOffset = ((slideIndex + _.options.slidesToShow) - _.slideCount) * verticalHeight;
            }
        }

        if (_.slideCount <= _.options.slidesToShow) {
            _.slideOffset = 0;
            verticalOffset = 0;
        }

        if (_.options.centerMode === true && _.slideCount <= _.options.slidesToShow) {
            _.slideOffset = ((_.slideWidth * Math.floor(_.options.slidesToShow)) / 2) - ((_.slideWidth * _.slideCount) / 2);
        } else if (_.options.centerMode === true && _.options.infinite === true) {
            _.slideOffset += _.slideWidth * Math.floor(_.options.slidesToShow / 2) - _.slideWidth;
        } else if (_.options.centerMode === true) {
            _.slideOffset = 0;
            _.slideOffset += _.slideWidth * Math.floor(_.options.slidesToShow / 2);
        }

        if (_.options.vertical === false) {
            targetLeft = ((slideIndex * _.slideWidth) * -1) + _.slideOffset;
        } else {
            targetLeft = ((slideIndex * verticalHeight) * -1) + verticalOffset;
        }

        if (_.options.variableWidth === true) {

            if (_.slideCount <= _.options.slidesToShow || _.options.infinite === false) {
                targetSlide = _.$slideTrack.children('.a4j-slick-slide').eq(slideIndex);
            } else {
                targetSlide = _.$slideTrack.children('.a4j-slick-slide').eq(slideIndex + _.options.slidesToShow);
            }

            if (_.options.rtl === true) {
                if (targetSlide[0]) {
                    targetLeft = (_.$slideTrack.width() - targetSlide[0].offsetLeft - targetSlide.width()) * -1;
                } else {
                    targetLeft =  0;
                }
            } else {
                targetLeft = targetSlide[0] ? targetSlide[0].offsetLeft * -1 : 0;
            }

            if (_.options.centerMode === true) {
                if (_.slideCount <= _.options.slidesToShow || _.options.infinite === false) {
                    targetSlide = _.$slideTrack.children('.a4j-slick-slide').eq(slideIndex);
                } else {
                    targetSlide = _.$slideTrack.children('.a4j-slick-slide').eq(slideIndex + _.options.slidesToShow + 1);
                }

                if (_.options.rtl === true) {
                    if (targetSlide[0]) {
                        targetLeft = (_.$slideTrack.width() - targetSlide[0].offsetLeft - targetSlide.width()) * -1;
                    } else {
                        targetLeft =  0;
                    }
                } else {
                    targetLeft = targetSlide[0] ? targetSlide[0].offsetLeft * -1 : 0;
                }

                targetLeft += (_.$list.width() - targetSlide.outerWidth()) / 2;
            }
        }

        return targetLeft;

    };

    Slick.prototype.getOption = Slick.prototype.slickGetOption = function(option) {

        var _ = this;

        return _.options[option];

    };

    Slick.prototype.getNavigableIndexes = function() {

        var _ = this,
            breakPoint = 0,
            counter = 0,
            indexes = [],
            max;

        if (_.options.infinite === false) {
            max = _.slideCount;
        } else {
            breakPoint = _.options.slidesToScroll * -1;
            counter = _.options.slidesToScroll * -1;
            max = _.slideCount * 2;
        }

        while (breakPoint < max) {
            indexes.push(breakPoint);
            breakPoint = counter + _.options.slidesToScroll;
            counter += _.options.slidesToScroll <= _.options.slidesToShow ? _.options.slidesToScroll : _.options.slidesToShow;
        }

        return indexes;

    };

    Slick.prototype.getSlick = function() {

        return this;

    };

    Slick.prototype.getSlideCount = function() {

        var _ = this,
            slidesTraversed, swipedSlide, centerOffset;

        centerOffset = _.options.centerMode === true ? _.slideWidth * Math.floor(_.options.slidesToShow / 2) : 0;

        if (_.options.swipeToSlide === true) {
            _.$slideTrack.find('.a4j-slick-slide').each(function(index, slide) {
                if (slide.offsetLeft - centerOffset + ($(slide).outerWidth() / 2) > (_.swipeLeft * -1)) {
                    swipedSlide = slide;
                    return false;
                }
            });

            slidesTraversed = Math.abs($(swipedSlide).attr('data-slick-index') - _.currentSlide) || 1;

            return slidesTraversed;

        } else {
            return _.options.slidesToScroll;
        }

    };

    Slick.prototype.goTo = Slick.prototype.slickGoTo = function(slide, dontAnimate) {

        var _ = this;

        _.changeSlide({
            data: {
                message: 'index',
                index: parseInt(slide)
            }
        }, dontAnimate);

    };

    Slick.prototype.init = function(creation) {

        var _ = this;
        if (!_.$slider.hasClass('a4j-slick-initialized')) {

            _.$slider.addClass('a4j-slick-initialized');

            _.buildRows();
            _.buildOut();
            _.setProps();
            _.startLoad();
            _.loadSlider();
            _.initializeEvents();
            _.updateArrows();
            _.updateDots();
            _.checkResponsive(true);
            _.focusHandler();

        }

        if (creation) {
            _.$slider.trigger('init', [_]);
        }

        if (_.options.accessibility === true) {
            _.initADA();
        }

        if ( _.options.autoplay ) {

            _.paused = false;
            _.autoPlay();

        }

    };

    Slick.prototype.initADA = function() {
        var _ = this;
        _.$slides.add(_.$slideTrack.find('.a4j-slick-cloned')).attr({
            'aria-hidden': 'true',
            'tabindex': '-1'
        }).find('a, input, button, select').attr({
            'tabindex': '-1'
        });

        _.$slideTrack.attr('role', 'listbox');

        _.$slides.not(_.$slideTrack.find('.a4j-slick-cloned')).each(function(i) {
            $(this).attr('role', 'option');
            
            //Evenly distribute aria-describedby tags through available dots.
            var describedBySlideId = _.options.centerMode ? i : Math.floor(i / _.options.slidesToShow);
            
            if (_.options.dots === true) {
                $(this).attr('aria-describedby', 'slick-slide' + _.instanceUid + describedBySlideId + '');
            }
        });

        if (_.$dots !== null) {
            _.$dots.attr('role', 'tablist').find('i').each(function(i) {
                $(this).attr({
                    'role': 'presentation',
                    'aria-selected': 'false',
                    'aria-controls': 'navigation' + _.instanceUid + i + '',
                    'id': 'slick-slide' + _.instanceUid + i + ''
                });
            })
                .first().attr('aria-selected', 'true').end()
                .find('button').attr('role', 'button').end()
                .closest('div').attr('role', 'toolbar');
        }
        _.activateADA();

    };

    Slick.prototype.initArrowEvents = function() {

        var _ = this;

        if (_.options.arrows === true && _.slideCount > _.options.slidesToShow) {
            _.$prevArrow
               .off('click.slick')
               .on('click.slick', {
                    message: 'previous'
               }, _.changeSlide);
            _.$nextArrow
               .off('click.slick')
               .on('click.slick', {
                    message: 'next'
               }, _.changeSlide);
        }

    };

    Slick.prototype.initDotEvents = function() {

        var _ = this;

        if (_.options.dots === true && _.slideCount > _.options.slidesToShow) {
            $('i', _.$dots).on('click.slick', {
                message: 'index'
            }, _.changeSlide);
        }

        if ( _.options.dots === true && _.options.pauseOnDotsHover === true ) {

            $('i', _.$dots)
                .on('mouseenter.slick', $.proxy(_.interrupt, _, true))
                .on('mouseleave.slick', $.proxy(_.interrupt, _, false));

        }

    };

    Slick.prototype.initSlideEvents = function() {

        var _ = this;

        if ( _.options.pauseOnHover ) {

            _.$list.on('mouseenter.slick', $.proxy(_.interrupt, _, true));
            _.$list.on('mouseleave.slick', $.proxy(_.interrupt, _, false));

        }

    };

    Slick.prototype.initializeEvents = function() {

        var _ = this;

        _.initArrowEvents();

        _.initDotEvents();
        _.initSlideEvents();

        _.$list.on('touchstart.slick mousedown.slick', {
            action: 'start'
        }, _.swipeHandler);
        _.$list.on('touchmove.slick mousemove.slick', {
            action: 'move'
        }, _.swipeHandler);
        _.$list.on('touchend.slick mouseup.slick', {
            action: 'end'
        }, _.swipeHandler);
        _.$list.on('touchcancel.slick mouseleave.slick', {
            action: 'end'
        }, _.swipeHandler);

        _.$list.on('click.slick', _.clickHandler);

        $(document).on(_.visibilityChange, $.proxy(_.visibility, _));

        if (_.options.accessibility === true) {
            _.$list.on('keydown.slick', _.keyHandler);
        }

        if (_.options.focusOnSelect === true) {
            $(_.$slideTrack).children().on('click.slick', _.selectHandler);
        }

        $(window).on('orientationchange.slick.slick-' + _.instanceUid, $.proxy(_.orientationChange, _));

        $(window).on('resize.slick.slick-' + _.instanceUid, $.proxy(_.resize, _));

        $('[draggable!=true]', _.$slideTrack).on('dragstart', _.preventDefault);

        $(window).on('load.slick.slick-' + _.instanceUid, _.setPosition);
        $(document).on('ready.slick.slick-' + _.instanceUid, _.setPosition);

    };

    Slick.prototype.initUI = function() {

        var _ = this;

        if (_.options.arrows === true && _.slideCount > _.options.slidesToShow) {

            _.$prevArrow.show();
            _.$nextArrow.show();

        }

        if (_.options.dots === true && _.slideCount > _.options.slidesToShow) {

            _.$dots.show();

        }

    };

    Slick.prototype.keyHandler = function(event) {

        var _ = this;
         //Dont slide if the cursor is inside the form fields and arrow keys are pressed
        if(!event.target.tagName.match('TEXTAREA|INPUT|SELECT')) {
            if (event.keyCode === 37 && _.options.accessibility === true) {
                _.changeSlide({
                    data: {
                        message: _.options.rtl === true ? 'next' :  'previous'
                    }
                });
            } else if (event.keyCode === 39 && _.options.accessibility === true) {
                _.changeSlide({
                    data: {
                        message: _.options.rtl === true ? 'previous' : 'next'
                    }
                });
            }
        }

    };

    Slick.prototype.lazyLoad = function() {

        var _ = this,
            loadRange, cloneRange, rangeStart, rangeEnd;

        function loadImages(imagesScope) {

            $('img[data-lazy]', imagesScope).each(function() {

                var image = $(this),
                    imageSource = $(this).attr('data-lazy'),
                    imageToLoad = document.createElement('img');

                imageToLoad.onload = function() {

                    image
                        .animate({ opacity: 0 }, 100, function() {
                            image
                                .attr('src', imageSource)
                                .animate({ opacity: 1 }, 200, function() {
                                    image
                                        .removeAttr('data-lazy')
                                        .removeClass('a4j-slick-loading');
                                });
                            _.$slider.trigger('lazyLoaded', [_, image, imageSource]);
                        });

                };

                imageToLoad.onerror = function() {

                    image
                        .removeAttr( 'data-lazy' )
                        .removeClass( 'a4j-slick-loading' )
                        .addClass( 'a4j-slick-lazyload-error' );

                    _.$slider.trigger('lazyLoadError', [ _, image, imageSource ]);

                };

                imageToLoad.src = imageSource;

            });

        }

        if (_.options.centerMode === true) {
            if (_.options.infinite === true) {
                rangeStart = _.currentSlide + (_.options.slidesToShow / 2 + 1);
                rangeEnd = rangeStart + _.options.slidesToShow + 2;
            } else {
                rangeStart = Math.max(0, _.currentSlide - (_.options.slidesToShow / 2 + 1));
                rangeEnd = 2 + (_.options.slidesToShow / 2 + 1) + _.currentSlide;
            }
        } else {
            rangeStart = _.options.infinite ? _.options.slidesToShow + _.currentSlide : _.currentSlide;
            rangeEnd = Math.ceil(rangeStart + _.options.slidesToShow);
            if (_.options.fade === true) {
                if (rangeStart > 0) rangeStart--;
                if (rangeEnd <= _.slideCount) rangeEnd++;
            }
        }

        loadRange = _.$slider.find('.a4j-slick-slide').slice(rangeStart, rangeEnd);
        loadImages(loadRange);

        if (_.slideCount <= _.options.slidesToShow) {
            cloneRange = _.$slider.find('.a4j-slick-slide');
            loadImages(cloneRange);
        } else
        if (_.currentSlide >= _.slideCount - _.options.slidesToShow) {
            cloneRange = _.$slider.find('.a4j-slick-cloned').slice(0, _.options.slidesToShow);
            loadImages(cloneRange);
        } else if (_.currentSlide === 0) {
            cloneRange = _.$slider.find('.a4j-slick-cloned').slice(_.options.slidesToShow * -1);
            loadImages(cloneRange);
        }

    };

    Slick.prototype.loadSlider = function() {

        var _ = this;

        _.setPosition();

        _.$slideTrack.css({
            opacity: 1
        });

        _.$slider.removeClass('a4j-slick-loading');

        _.initUI();

        if (_.options.lazyLoad === 'progressive') {
            _.progressiveLazyLoad();
        }

    };

    Slick.prototype.next = Slick.prototype.slickNext = function() {

        var _ = this;

        _.changeSlide({
            data: {
                message: 'next'
            }
        });

    };

    Slick.prototype.orientationChange = function() {

        var _ = this;

        _.checkResponsive();
        _.setPosition();

    };

    Slick.prototype.pause = Slick.prototype.slickPause = function() {

        var _ = this;

        _.autoPlayClear();
        _.paused = true;

    };

    Slick.prototype.play = Slick.prototype.slickPlay = function() {

        var _ = this;

        _.autoPlay();
        _.options.autoplay = true;
        _.paused = false;
        _.focussed = false;
        _.interrupted = false;

    };

    Slick.prototype.postSlide = function(index) {

        var _ = this;

        if( !_.unslicked ) {

            _.$slider.trigger('afterChange', [_, index]);

            _.animating = false;

            _.setPosition();

            _.swipeLeft = null;

            if ( _.options.autoplay ) {
                _.autoPlay();
            }

            if (_.options.accessibility === true) {
                _.initADA();
            }

        }

    };

    Slick.prototype.prev = Slick.prototype.slickPrev = function() {

        var _ = this;

        _.changeSlide({
            data: {
                message: 'previous'
            }
        });

    };

    Slick.prototype.preventDefault = function(event) {

        event.preventDefault();

    };

    Slick.prototype.progressiveLazyLoad = function( tryCount ) {

        tryCount = tryCount || 1;

        var _ = this,
            $imgsToLoad = $( 'img[data-lazy]', _.$slider ),
            image,
            imageSource,
            imageToLoad;

        if ( $imgsToLoad.length ) {

            image = $imgsToLoad.first();
            imageSource = image.attr('data-lazy');
            imageToLoad = document.createElement('img');

            imageToLoad.onload = function() {

                image
                    .attr( 'src', imageSource )
                    .removeAttr('data-lazy')
                    .removeClass('a4j-slick-loading');

                if ( _.options.adaptiveHeight === true ) {
                    _.setPosition();
                }

                _.$slider.trigger('lazyLoaded', [ _, image, imageSource ]);
                _.progressiveLazyLoad();

            };

            imageToLoad.onerror = function() {

                if ( tryCount < 3 ) {

                    /**
                     * try to load the image 3 times,
                     * leave a slight delay so we don't get
                     * servers blocking the request.
                     */
                    setTimeout( function() {
                        _.progressiveLazyLoad( tryCount + 1 );
                    }, 500 );

                } else {

                    image
                        .removeAttr( 'data-lazy' )
                        .removeClass( 'a4j-slick-loading' )
                        .addClass( 'a4j-slick-lazyload-error' );

                    _.$slider.trigger('lazyLoadError', [ _, image, imageSource ]);

                    _.progressiveLazyLoad();

                }

            };

            imageToLoad.src = imageSource;

        } else {

            _.$slider.trigger('allImagesLoaded', [ _ ]);

        }

    };

    Slick.prototype.refresh = function( initializing ) {

        var _ = this, currentSlide, lastVisibleIndex;

        lastVisibleIndex = _.slideCount - _.options.slidesToShow;

        // in non-infinite sliders, we don't want to go past the
        // last visible index.
        if( !_.options.infinite && ( _.currentSlide > lastVisibleIndex )) {
            _.currentSlide = lastVisibleIndex;
        }

        // if less slides than to show, go to start.
        if ( _.slideCount <= _.options.slidesToShow ) {
            _.currentSlide = 0;

        }

        currentSlide = _.currentSlide;

        _.destroy(true);

        $.extend(_, _.initials, { currentSlide: currentSlide });

        _.init();

        if( !initializing ) {

            _.changeSlide({
                data: {
                    message: 'index',
                    index: currentSlide
                }
            }, false);

        }

    };

    Slick.prototype.registerBreakpoints = function() {

        var _ = this, breakpoint, currentBreakpoint, l,
            responsiveSettings = _.options.responsive || null;

        if ( $.type(responsiveSettings) === 'array' && responsiveSettings.length ) {

            _.respondTo = _.options.respondTo || 'window';

            for ( breakpoint in responsiveSettings ) {

                l = _.breakpoints.length-1;
                currentBreakpoint = responsiveSettings[breakpoint].breakpoint;

                if (responsiveSettings.hasOwnProperty(breakpoint)) {

                    // loop through the breakpoints and cut out any existing
                    // ones with the same breakpoint number, we don't want dupes.
                    while( l >= 0 ) {
                        if( _.breakpoints[l] && _.breakpoints[l] === currentBreakpoint ) {
                            _.breakpoints.splice(l,1);
                        }
                        l--;
                    }

                    _.breakpoints.push(currentBreakpoint);
                    _.breakpointSettings[currentBreakpoint] = responsiveSettings[breakpoint].settings;

                }

            }

            _.breakpoints.sort(function(a, b) {
                return ( _.options.mobileFirst ) ? a-b : b-a;
            });

        }

    };

    Slick.prototype.reinit = function() {

        var _ = this;

        _.$slides =
            _.$slideTrack
                .children(_.options.slide)
                .addClass('a4j-slick-slide');

        _.slideCount = _.$slides.length;

        if (_.currentSlide >= _.slideCount && _.currentSlide !== 0) {
            _.currentSlide = _.currentSlide - _.options.slidesToScroll;
        }

        if (_.slideCount <= _.options.slidesToShow) {
            _.currentSlide = 0;
        }

        _.registerBreakpoints();

        _.setProps();
        _.setupInfinite();
        _.buildArrows();
        _.updateArrows();
        _.initArrowEvents();
        _.buildDots();
        _.updateDots();
        _.initDotEvents();
        _.cleanUpSlideEvents();
        _.initSlideEvents();

        _.checkResponsive(false, true);

        if (_.options.focusOnSelect === true) {
            $(_.$slideTrack).children().on('click.slick', _.selectHandler);
        }

        _.setSlideClasses(typeof _.currentSlide === 'number' ? _.currentSlide : 0);

        _.setPosition();
        _.focusHandler();

        _.paused = !_.options.autoplay;
        _.autoPlay();

        _.$slider.trigger('reInit', [_]);

    };

    Slick.prototype.resize = function() {

        var _ = this;

        if ($(window).width() !== _.windowWidth) {
            clearTimeout(_.windowDelay);
            _.windowDelay = window.setTimeout(function() {
                _.windowWidth = $(window).width();
                _.checkResponsive();
                if( !_.unslicked ) { _.setPosition(); }
            }, 50);
        }
    };

    Slick.prototype.removeSlide = Slick.prototype.slickRemove = function(index, removeBefore, removeAll) {

        var _ = this;

        if (typeof(index) === 'boolean') {
            removeBefore = index;
            index = removeBefore === true ? 0 : _.slideCount - 1;
        } else {
            index = removeBefore === true ? --index : index;
        }

        if (_.slideCount < 1 || index < 0 || index > _.slideCount - 1) {
            return false;
        }

        _.unload();

        if (removeAll === true) {
            _.$slideTrack.children().remove();
        } else {
            _.$slideTrack.children(this.options.slide).eq(index).remove();
        }

        _.$slides = _.$slideTrack.children(this.options.slide);

        _.$slideTrack.children(this.options.slide).detach();

        _.$slideTrack.append(_.$slides);

        _.$slidesCache = _.$slides;

        _.reinit();

    };

    Slick.prototype.setCSS = function(position) {

        var _ = this,
            positionProps = {},
            x, y;

        if (_.options.rtl === true) {
            position = -position;
        }
        x = _.positionProp == 'left' ? Math.ceil(position) + 'px' : '0px';
        y = _.positionProp == 'top' ? Math.ceil(position) + 'px' : '0px';

        positionProps[_.positionProp] = position;

        if (_.transformsEnabled === false) {
            _.$slideTrack.css(positionProps);
        } else {
            positionProps = {};
            if (_.cssTransitions === false) {
                positionProps[_.animType] = 'translate(' + x + ', ' + y + ')';
                _.$slideTrack.css(positionProps);
            } else {
                positionProps[_.animType] = 'translate3d(' + x + ', ' + y + ', 0px)';
                _.$slideTrack.css(positionProps);
            }
        }

    };

    Slick.prototype.setDimensions = function() {

        var _ = this;

        if (_.options.vertical === false) {
            if (_.options.centerMode === true) {
                _.$list.css({
                    padding: ('0px ' + _.options.centerPadding)
                });
            }
        } else {
            _.$list.height(_.$slides.first().outerHeight(true) * _.options.slidesToShow);
            if (_.options.centerMode === true) {
                _.$list.css({
                    padding: (_.options.centerPadding + ' 0px')
                });
            }
        }

        _.listWidth = _.$list.width();
        _.listHeight = _.$list.height();


        if (_.options.vertical === false && _.options.variableWidth === false) {
            _.slideWidth = Math.ceil(_.listWidth / _.options.slidesToShow);
            _.$slideTrack.width(Math.ceil((_.slideWidth * _.$slideTrack.children('.a4j-slick-slide').length)));

        } else if (_.options.variableWidth === true) {
            _.$slideTrack.width(5000 * _.slideCount);
        } else {
            _.slideWidth = Math.ceil(_.listWidth);
            _.$slideTrack.height(Math.ceil((_.$slides.first().outerHeight(true) * _.$slideTrack.children('.a4j-slick-slide').length)));
        }

        var offset = _.$slides.first().outerWidth(true) - _.$slides.first().width();
        if (_.options.variableWidth === false) _.$slideTrack.children('.a4j-slick-slide').width(_.slideWidth - offset);

    };

    Slick.prototype.setFade = function() {

        var _ = this,
            targetLeft;

        _.$slides.each(function(index, element) {
            targetLeft = (_.slideWidth * index) * -1;
            if (_.options.rtl === true) {
                $(element).css({
                    position: 'relative',
                    right: targetLeft,
                    top: 0,
                    zIndex: _.options.zIndex - 2,
                    opacity: 0
                });
            } else {
                $(element).css({
                    position: 'relative',
                    left: targetLeft,
                    top: 0,
                    zIndex: _.options.zIndex - 2,
                    opacity: 0
                });
            }
        });

        _.$slides.eq(_.currentSlide).css({
            zIndex: _.options.zIndex - 1,
            opacity: 1
        });

    };

    Slick.prototype.setHeight = function() {

        var _ = this;

        if (_.options.slidesToShow === 1 && _.options.adaptiveHeight === true && _.options.vertical === false) {
            var targetHeight = _.$slides.eq(_.currentSlide).outerHeight(true);
            _.$list.css('height', targetHeight);
        }

    };

    Slick.prototype.setOption =
    Slick.prototype.slickSetOption = function() {

        /**
         * accepts arguments in format of:
         *
         *  - for changing a single option's value:
         *     .a4jslick("setOption", option, value, refresh )
         *
         *  - for changing a set of responsive options:
         *     .a4jslick("setOption", 'responsive', [{}, ...], refresh )
         *
         *  - for updating multiple values at once (not responsive)
         *     .a4jslick("setOption", { 'option': value, ... }, refresh )
         */

        var _ = this, l, item, option, value, refresh = false, type;

        if( $.type( arguments[0] ) === 'object' ) {

            option =  arguments[0];
            refresh = arguments[1];
            type = 'multiple';

        } else if ( $.type( arguments[0] ) === 'string' ) {

            option =  arguments[0];
            value = arguments[1];
            refresh = arguments[2];

            if ( arguments[0] === 'responsive' && $.type( arguments[1] ) === 'array' ) {

                type = 'responsive';

            } else if ( typeof arguments[1] !== 'undefined' ) {

                type = 'single';

            }

        }

        if ( type === 'single' ) {

            _.options[option] = value;


        } else if ( type === 'multiple' ) {

            $.each( option , function( opt, val ) {

                _.options[opt] = val;

            });


        } else if ( type === 'responsive' ) {

            for ( item in value ) {

                if( $.type( _.options.responsive ) !== 'array' ) {

                    _.options.responsive = [ value[item] ];

                } else {

                    l = _.options.responsive.length-1;

                    // loop through the responsive object and splice out duplicates.
                    while( l >= 0 ) {

                        if( _.options.responsive[l].breakpoint === value[item].breakpoint ) {

                            _.options.responsive.splice(l,1);

                        }

                        l--;

                    }

                    _.options.responsive.push( value[item] );

                }

            }

        }

        if ( refresh ) {

            _.unload();
            _.reinit();

        }

    };

    Slick.prototype.setPosition = function() {

        var _ = this;

        _.setDimensions();

        _.setHeight();

        if (_.options.fade === false) {
            _.setCSS(_.getLeft(_.currentSlide));
        } else {
            _.setFade();
        }

        _.$slider.trigger('setPosition', [_]);

    };

    Slick.prototype.setProps = function() {

        var _ = this,
            bodyStyle = document.body.style;

        _.positionProp = _.options.vertical === true ? 'top' : 'left';

        if (_.positionProp === 'top') {
            _.$slider.addClass('a4j-slick-vertical');
        } else {
            _.$slider.removeClass('a4j-slick-vertical');
        }

        if (bodyStyle.WebkitTransition !== undefined ||
            bodyStyle.MozTransition !== undefined ||
            bodyStyle.msTransition !== undefined) {
            if (_.options.useCSS === true) {
                _.cssTransitions = true;
            }
        }

        if ( _.options.fade ) {
            if ( typeof _.options.zIndex === 'number' ) {
                if( _.options.zIndex < 3 ) {
                    _.options.zIndex = 3;
                }
            } else {
                _.options.zIndex = _.defaults.zIndex;
            }
        }

        if (bodyStyle.OTransform !== undefined) {
            _.animType = 'OTransform';
            _.transformType = '-o-transform';
            _.transitionType = 'OTransition';
            if (bodyStyle.perspectiveProperty === undefined && bodyStyle.webkitPerspective === undefined) _.animType = false;
        }
        if (bodyStyle.MozTransform !== undefined) {
            _.animType = 'MozTransform';
            _.transformType = '-moz-transform';
            _.transitionType = 'MozTransition';
            if (bodyStyle.perspectiveProperty === undefined && bodyStyle.MozPerspective === undefined) _.animType = false;
        }
        if (bodyStyle.webkitTransform !== undefined) {
            _.animType = 'webkitTransform';
            _.transformType = '-webkit-transform';
            _.transitionType = 'webkitTransition';
            if (bodyStyle.perspectiveProperty === undefined && bodyStyle.webkitPerspective === undefined) _.animType = false;
        }
        if (bodyStyle.msTransform !== undefined) {
            _.animType = 'msTransform';
            _.transformType = '-ms-transform';
            _.transitionType = 'msTransition';
            if (bodyStyle.msTransform === undefined) _.animType = false;
        }
        if (bodyStyle.transform !== undefined && _.animType !== false) {
            _.animType = 'transform';
            _.transformType = 'transform';
            _.transitionType = 'transition';
        }
        _.transformsEnabled = _.options.useTransform && (_.animType !== null && _.animType !== false);
    };


    Slick.prototype.setSlideClasses = function(index) {

        var _ = this,
            centerOffset, allSlides, indexOffset, remainder;

        allSlides = _.$slider
            .find('.a4j-slick-slide')
            .removeClass('a4j-slick-active a4j-slick-center a4j-slick-current')
            .attr('aria-hidden', 'true');

        _.$slides
            .eq(index)
            .addClass('a4j-slick-current');

        if (_.options.centerMode === true) {

            centerOffset = Math.floor(_.options.slidesToShow / 2);

            if (_.options.infinite === true) {

                if (index >= centerOffset && index <= (_.slideCount - 1) - centerOffset) {

                    _.$slides
                        .slice(index - centerOffset, index + centerOffset + 1)
                        .addClass('a4j-slick-active')
                        .attr('aria-hidden', 'false');

                } else {

                    indexOffset = _.options.slidesToShow + index;
                    allSlides
                        .slice(indexOffset - centerOffset + 1, indexOffset + centerOffset + 2)
                        .addClass('a4j-slick-active')
                        .attr('aria-hidden', 'false');

                }

                if (index === 0) {

                    allSlides
                        .eq(allSlides.length - 1 - _.options.slidesToShow)
                        .addClass('a4j-slick-center');

                } else if (index === _.slideCount - 1) {

                    allSlides
                        .eq(_.options.slidesToShow)
                        .addClass('a4j-slick-center');

                }

            }

            _.$slides
                .eq(index)
                .addClass('a4j-slick-center');

        } else {

            if (index >= 0 && index <= (_.slideCount - _.options.slidesToShow)) {

                _.$slides
                    .slice(index, index + _.options.slidesToShow)
                    .addClass('a4j-slick-active')
                    .attr('aria-hidden', 'false');

            } else if (allSlides.length <= _.options.slidesToShow) {

                allSlides
                    .addClass('a4j-slick-active')
                    .attr('aria-hidden', 'false');

            } else {

                remainder = _.slideCount % _.options.slidesToShow;
                indexOffset = _.options.infinite === true ? _.options.slidesToShow + index : index;

                if (_.options.slidesToShow == _.options.slidesToScroll && (_.slideCount - index) < _.options.slidesToShow) {

                    allSlides
                        .slice(indexOffset - (_.options.slidesToShow - remainder), indexOffset + remainder)
                        .addClass('a4j-slick-active')
                        .attr('aria-hidden', 'false');

                } else {

                    allSlides
                        .slice(indexOffset, indexOffset + _.options.slidesToShow)
                        .addClass('a4j-slick-active')
                        .attr('aria-hidden', 'false');

                }

            }

        }

        if (_.options.lazyLoad === 'ondemand') {
            _.lazyLoad();
        }

    };

    Slick.prototype.setupInfinite = function() {

        var _ = this,
            i, slideIndex, infiniteCount;

        if (_.options.fade === true) {
            _.options.centerMode = false;
        }

        if (_.options.infinite === true && _.options.fade === false) {

            slideIndex = null;

            if (_.slideCount > _.options.slidesToShow) {

                if (_.options.centerMode === true) {
                    infiniteCount = _.options.slidesToShow + 1;
                } else {
                    infiniteCount = _.options.slidesToShow;
                }

                for (i = _.slideCount; i > (_.slideCount -
                        infiniteCount); i -= 1) {
                    slideIndex = i - 1;
                    $(_.$slides[slideIndex]).clone(true).attr('id', '')
                        .attr('data-slick-index', slideIndex - _.slideCount)
                        .prependTo(_.$slideTrack).addClass('a4j-slick-cloned');
                }
                for (i = 0; i < infiniteCount; i += 1) {
                    slideIndex = i;
                    $(_.$slides[slideIndex]).clone(true).attr('id', '')
                        .attr('data-slick-index', slideIndex + _.slideCount)
                        .appendTo(_.$slideTrack).addClass('a4j-slick-cloned');
                }
                _.$slideTrack.find('.a4j-slick-cloned').find('[id]').each(function() {
                    $(this).attr('id', '');
                });

            }

        }

    };

    Slick.prototype.interrupt = function( toggle ) {

        var _ = this;

        if( !toggle ) {
            _.autoPlay();
        }
        _.interrupted = toggle;

    };

    Slick.prototype.selectHandler = function(event) {

        var _ = this;

        var targetElement =
            $(event.target).is('.a4j-slick-slide') ?
                $(event.target) :
                $(event.target).parents('.a4j-slick-slide');

        var index = parseInt(targetElement.attr('data-slick-index'));

        if (!index) index = 0;

        if (_.slideCount <= _.options.slidesToShow) {

            _.setSlideClasses(index);
            _.asNavFor(index);
            return;

        }

        _.slideHandler(index);

    };

    Slick.prototype.slideHandler = function(index, sync, dontAnimate) {

        var targetSlide, animSlide, oldSlide, slideLeft, targetLeft = null,
            _ = this, navTarget;

        sync = sync || false;

        if (_.animating === true && _.options.waitForAnimate === true) {
            return;
        }

        if (_.options.fade === true && _.currentSlide === index) {
            return;
        }

        if (_.slideCount <= _.options.slidesToShow) {
            return;
        }

        if (sync === false) {
            _.asNavFor(index);
        }

        targetSlide = index;
        targetLeft = _.getLeft(targetSlide);
        slideLeft = _.getLeft(_.currentSlide);

        _.currentLeft = _.swipeLeft === null ? slideLeft : _.swipeLeft;

        if (_.options.infinite === false && _.options.centerMode === false && (index < 0 || index > _.getDotCount() * _.options.slidesToScroll)) {
            if (_.options.fade === false) {
                targetSlide = _.currentSlide;
                if (dontAnimate !== true) {
                    _.animateSlide(slideLeft, function() {
                        _.postSlide(targetSlide);
                    });
                } else {
                    _.postSlide(targetSlide);
                }
            }
            return;
        } else if (_.options.infinite === false && _.options.centerMode === true && (index < 0 || index > (_.slideCount - _.options.slidesToScroll))) {
            if (_.options.fade === false) {
                targetSlide = _.currentSlide;
                if (dontAnimate !== true) {
                    _.animateSlide(slideLeft, function() {
                        _.postSlide(targetSlide);
                    });
                } else {
                    _.postSlide(targetSlide);
                }
            }
            return;
        }

        if ( _.options.autoplay ) {
            clearInterval(_.autoPlayTimer);
        }

        if (targetSlide < 0) {
            if (_.slideCount % _.options.slidesToScroll !== 0) {
                animSlide = _.slideCount - (_.slideCount % _.options.slidesToScroll);
            } else {
                animSlide = _.slideCount + targetSlide;
            }
        } else if (targetSlide >= _.slideCount) {
            if (_.slideCount % _.options.slidesToScroll !== 0) {
                animSlide = 0;
            } else {
                animSlide = targetSlide - _.slideCount;
            }
        } else {
            animSlide = targetSlide;
        }

        _.animating = true;
		
		_.$slider.closest('.ingallery').trigger('beforeCarouselChange', [_, _.currentSlide, animSlide]);
        _.$slider.trigger('beforeChange', [_, _.currentSlide, animSlide]);

        oldSlide = _.currentSlide;
        _.currentSlide = animSlide;

        _.setSlideClasses(_.currentSlide);

        if ( _.options.asNavFor ) {

            navTarget = _.getNavTarget();
            navTarget = navTarget.a4jslick('getSlick');

            if ( navTarget.slideCount <= navTarget.options.slidesToShow ) {
                navTarget.setSlideClasses(_.currentSlide);
            }

        }

        _.updateDots();
        _.updateArrows();

        if (_.options.fade === true) {
            if (dontAnimate !== true) {

                _.fadeSlideOut(oldSlide);

                _.fadeSlide(animSlide, function() {
                    _.postSlide(animSlide);
                });

            } else {
                _.postSlide(animSlide);
            }
            _.animateHeight();
            return;
        }

        if (dontAnimate !== true) {
            _.animateSlide(targetLeft, function() {
                _.postSlide(animSlide);
            });
        } else {
            _.postSlide(animSlide);
        }

    };

    Slick.prototype.startLoad = function() {

        var _ = this;

        if (_.options.arrows === true && _.slideCount > _.options.slidesToShow) {

            _.$prevArrow.hide();
            _.$nextArrow.hide();

        }

        if (_.options.dots === true && _.slideCount > _.options.slidesToShow) {

            _.$dots.hide();

        }

        _.$slider.addClass('a4j-slick-loading');

    };

    Slick.prototype.swipeDirection = function() {

        var xDist, yDist, r, swipeAngle, _ = this;

        xDist = _.touchObject.startX - _.touchObject.curX;
        yDist = _.touchObject.startY - _.touchObject.curY;
        r = Math.atan2(yDist, xDist);

        swipeAngle = Math.round(r * 180 / Math.PI);
        if (swipeAngle < 0) {
            swipeAngle = 360 - Math.abs(swipeAngle);
        }

        if ((swipeAngle <= 45) && (swipeAngle >= 0)) {
            return (_.options.rtl === false ? 'left' : 'right');
        }
        if ((swipeAngle <= 360) && (swipeAngle >= 315)) {
            return (_.options.rtl === false ? 'left' : 'right');
        }
        if ((swipeAngle >= 135) && (swipeAngle <= 225)) {
            return (_.options.rtl === false ? 'right' : 'left');
        }
        if (_.options.verticalSwiping === true) {
            if ((swipeAngle >= 35) && (swipeAngle <= 135)) {
                return 'down';
            } else {
                return 'up';
            }
        }

        return 'vertical';

    };

    Slick.prototype.swipeEnd = function(event) {

        var _ = this,
            slideCount,
            direction;

        _.dragging = false;
        _.interrupted = false;
        _.shouldClick = ( _.touchObject.swipeLength > 10 ) ? false : true;

        if ( _.touchObject.curX === undefined ) {
            return false;
        }

        if ( _.touchObject.edgeHit === true ) {
            _.$slider.trigger('edge', [_, _.swipeDirection() ]);
        }

        if ( _.touchObject.swipeLength >= _.touchObject.minSwipe ) {

            direction = _.swipeDirection();

            switch ( direction ) {

                case 'left':
                case 'down':

                    slideCount =
                        _.options.swipeToSlide ?
                            _.checkNavigable( _.currentSlide + _.getSlideCount() ) :
                            _.currentSlide + _.getSlideCount();

                    _.currentDirection = 0;

                    break;

                case 'right':
                case 'up':

                    slideCount =
                        _.options.swipeToSlide ?
                            _.checkNavigable( _.currentSlide - _.getSlideCount() ) :
                            _.currentSlide - _.getSlideCount();

                    _.currentDirection = 1;

                    break;

                default:


            }

            if( direction != 'vertical' ) {

                _.slideHandler( slideCount );
                _.touchObject = {};
                _.$slider.trigger('swipe', [_, direction ]);

            }

        } else {

            if ( _.touchObject.startX !== _.touchObject.curX ) {

                _.slideHandler( _.currentSlide );
                _.touchObject = {};

            }

        }

    };

    Slick.prototype.swipeHandler = function(event) {

        var _ = this;

        if ((_.options.swipe === false) || ('ontouchend' in document && _.options.swipe === false)) {
            return;
        } else if (_.options.draggable === false && event.type.indexOf('mouse') !== -1) {
            return;
        }

        _.touchObject.fingerCount = event.originalEvent && event.originalEvent.touches !== undefined ?
            event.originalEvent.touches.length : 1;

        _.touchObject.minSwipe = _.listWidth / _.options
            .touchThreshold;

        if (_.options.verticalSwiping === true) {
            _.touchObject.minSwipe = _.listHeight / _.options
                .touchThreshold;
        }

        switch (event.data.action) {

            case 'start':
                _.swipeStart(event);
                break;

            case 'move':
                _.swipeMove(event);
                break;

            case 'end':
                _.swipeEnd(event);
                break;

        }

    };

    Slick.prototype.swipeMove = function(event) {

        var _ = this,
            edgeWasHit = false,
            curLeft, swipeDirection, swipeLength, positionOffset, touches;

        touches = event.originalEvent !== undefined ? event.originalEvent.touches : null;

        if (!_.dragging || touches && touches.length !== 1) {
            return false;
        }

        curLeft = _.getLeft(_.currentSlide);

        _.touchObject.curX = touches !== undefined ? touches[0].pageX : event.clientX;
        _.touchObject.curY = touches !== undefined ? touches[0].pageY : event.clientY;

        _.touchObject.swipeLength = Math.round(Math.sqrt(
            Math.pow(_.touchObject.curX - _.touchObject.startX, 2)));

        if (_.options.verticalSwiping === true) {
            _.touchObject.swipeLength = Math.round(Math.sqrt(
                Math.pow(_.touchObject.curY - _.touchObject.startY, 2)));
        }

        swipeDirection = _.swipeDirection();

        if (swipeDirection === 'vertical') {
            return;
        }

        if (event.originalEvent !== undefined && _.touchObject.swipeLength > 4) {
            event.preventDefault();
        }

        positionOffset = (_.options.rtl === false ? 1 : -1) * (_.touchObject.curX > _.touchObject.startX ? 1 : -1);
        if (_.options.verticalSwiping === true) {
            positionOffset = _.touchObject.curY > _.touchObject.startY ? 1 : -1;
        }


        swipeLength = _.touchObject.swipeLength;

        _.touchObject.edgeHit = false;

        if (_.options.infinite === false) {
            if ((_.currentSlide === 0 && swipeDirection === 'right') || (_.currentSlide >= _.getDotCount() && swipeDirection === 'left')) {
                swipeLength = _.touchObject.swipeLength * _.options.edgeFriction;
                _.touchObject.edgeHit = true;
            }
        }

        if (_.options.vertical === false) {
            _.swipeLeft = curLeft + swipeLength * positionOffset;
        } else {
            _.swipeLeft = curLeft + (swipeLength * (_.$list.height() / _.listWidth)) * positionOffset;
        }
        if (_.options.verticalSwiping === true) {
            _.swipeLeft = curLeft + swipeLength * positionOffset;
        }

        if (_.options.fade === true || _.options.touchMove === false) {
            return false;
        }

        if (_.animating === true) {
            _.swipeLeft = null;
            return false;
        }

        _.setCSS(_.swipeLeft);

    };

    Slick.prototype.swipeStart = function(event) {

        var _ = this,
            touches;

        _.interrupted = true;

        if (_.touchObject.fingerCount !== 1 || _.slideCount <= _.options.slidesToShow) {
            _.touchObject = {};
            return false;
        }

        if (event.originalEvent !== undefined && event.originalEvent.touches !== undefined) {
            touches = event.originalEvent.touches[0];
        }

        _.touchObject.startX = _.touchObject.curX = touches !== undefined ? touches.pageX : event.clientX;
        _.touchObject.startY = _.touchObject.curY = touches !== undefined ? touches.pageY : event.clientY;

        _.dragging = true;

    };

    Slick.prototype.unfilterSlides = Slick.prototype.slickUnfilter = function() {

        var _ = this;

        if (_.$slidesCache !== null) {

            _.unload();

            _.$slideTrack.children(this.options.slide).detach();

            _.$slidesCache.appendTo(_.$slideTrack);

            _.reinit();

        }

    };

    Slick.prototype.unload = function() {

        var _ = this;

        $('.a4j-slick-cloned', _.$slider).remove();

        if (_.$dots) {
            _.$dots.remove();
        }

        if (_.$prevArrow && _.htmlExpr.test(_.options.prevArrow)) {
            _.$prevArrow.remove();
        }

        if (_.$nextArrow && _.htmlExpr.test(_.options.nextArrow)) {
            _.$nextArrow.remove();
        }

        _.$slides
            .removeClass('a4j-slick-slide a4j-slick-active a4j-slick-visible a4j-slick-current')
            .attr('aria-hidden', 'true')
            .css('width', '');

    };

    Slick.prototype.unslick = function(fromBreakpoint) {

        var _ = this;
        _.$slider.trigger('unslick', [_, fromBreakpoint]);
        _.destroy();

    };

    Slick.prototype.updateArrows = function() {

        var _ = this,
            centerOffset;

        centerOffset = Math.floor(_.options.slidesToShow / 2);

        if ( _.options.arrows === true &&
            _.slideCount > _.options.slidesToShow &&
            !_.options.infinite ) {

            _.$prevArrow.removeClass('a4j-slick-disabled').attr('aria-disabled', 'false');
            _.$nextArrow.removeClass('a4j-slick-disabled').attr('aria-disabled', 'false');

            if (_.currentSlide === 0) {

                _.$prevArrow.addClass('a4j-slick-disabled').attr('aria-disabled', 'true');
                _.$nextArrow.removeClass('a4j-slick-disabled').attr('aria-disabled', 'false');

            } else if (_.currentSlide >= _.slideCount - _.options.slidesToShow && _.options.centerMode === false) {

                _.$nextArrow.addClass('a4j-slick-disabled').attr('aria-disabled', 'true');
                _.$prevArrow.removeClass('a4j-slick-disabled').attr('aria-disabled', 'false');

            } else if (_.currentSlide >= _.slideCount - 1 && _.options.centerMode === true) {

                _.$nextArrow.addClass('a4j-slick-disabled').attr('aria-disabled', 'true');
                _.$prevArrow.removeClass('a4j-slick-disabled').attr('aria-disabled', 'false');

            }

        }

    };

    Slick.prototype.updateDots = function() {

        var _ = this;

        if (_.$dots !== null) {

            _.$dots
                .find('i')
                .removeClass('a4j-slick-active')
                .attr('aria-hidden', 'true');

            _.$dots
                .find('i')
                .eq(Math.floor(_.currentSlide / _.options.slidesToScroll))
                .addClass('a4j-slick-active')
                .attr('aria-hidden', 'false');

        }

    };

    Slick.prototype.visibility = function() {

        var _ = this;

        if ( _.options.autoplay ) {

            if ( document[_.hidden] ) {

                _.interrupted = true;

            } else {

                _.interrupted = false;

            }

        }

    };

    $.fn.a4jSlick = function() {
        var _ = this,
            opt = arguments[0],
            args = Array.prototype.slice.call(arguments, 1),
            l = _.length,
            i,
            ret;
        for (i = 0; i < l; i++) {
            if (typeof opt == 'object' || typeof opt == 'undefined')
                _[i].a4jslick = new Slick(_[i], opt);
            else
                ret = _[i].a4jslick[opt].apply(_[i].a4jslick, args);
            if (typeof ret != 'undefined') return ret;
        }
        return _;
    };

})(jQuery);

(function ($) {

    $.fn.ingStepper = function (options) {

        var settings = $.extend({
            upClass: 'default',
            downClass: 'default',
            center: true
        }, options);

        return this.each(function (e) {
            var self = $(this);
			if(self.data('ingstepper')){
				return;
			}
            var clone = self.clone();
			clone.data('ingstepper',1);
			clone.addClass('ing-form-control');

            var min = self.attr('min');
            var max = self.attr('max');

            function setText(n) {
				if(isNaN(n)){
					n = 0;
				}
                if ((min && n < min) || (max && n > max)) {
                    return false;
                }

                clone.focus().val(n);
				clone.trigger('change');
                return true;
            }

            var group = $("<div class='ing-form-group ing-stepper'></div>");
            var down = $("<button type='button'>-</button>").attr('class', 'ing-btn ing-btn-' + settings.downClass).click(function () {
                setText(parseInt(clone.val()) - 1);
            });
            var up = $("<button type='button'>+</button>").attr('class', 'ing-btn ing-btn-' + settings.upClass).click(function () {
                setText(parseInt(clone.val()) + 1);
            });
            $("<span class='ing-form-group-addon ing-stepper-minus'></span>").append(down).appendTo(group);
            clone.appendTo(group);
            $("<span class='ing-form-group-addon ing-stepper-plus'></span>").append(up).appendTo(group);

            // remove spins from original
            clone.prop('type', 'text').keydown(function (e) {
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
					(e.keyCode == 65 && e.ctrlKey === true) ||
					(e.keyCode >= 35 && e.keyCode <= 39)) {
                    return;
                }
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }

                var c = String.fromCharCode(e.which);
                var n = parseInt(clone.val() + c);

                //if ((min && n < min) || (max && n > max)) {
                //    e.preventDefault();
                //}
            });

            clone.prop('type', 'text').blur(function (e) {
                var c = String.fromCharCode(e.which);
                var n = parseInt(clone.val() + c);
                if ((min && n < min)) {
                    setText(min);
                }
                else if (max && n > max) {
                    setText(max);
                }
            });


            self.replaceWith(group);
        });
    };
}(jQuery));

(function ($) {
    'use strict';

    if (window.inGallery) {
        return;
    }

    var popupShown = false;
    var fitPopupTimeout = 0;
    var popupShade = null;
    var popup = null;
    var loaderHTML = '<div class="ingLoaderWrap"><i class="ing-icon-spin2 animate-spin"></i></div>';

    var InGallery = function (htmlNode) {
        this.jqNode = $(htmlNode);
        this.id = this.jqNode.attr('data-id');
        this.cfg = JSON.parse(this.jqNode.attr('data-cfg'));
        this.page = parseInt(this.jqNode.attr('data-page'));
        this.commentsCache = {};
        this.layoutFitTimeout = 0;
        this.masonry = null;
        this.visible = false;

        var self = this;

        var loadMoreBtn = this.jqNode.find('.ingallery-loadmore-btn');
        var infScroll = this.jqNode.find('.ingallery-loadmore.ingallery-inf-scroll');

        this.jqNode.find('.ingallery-album').off('click').on('click', function () {
            self.filterGallery($(this).attr('data-id'));
        });
        if (this.cfg.layout_type == 'carousel') {
            this.jqNode.find('.ingallery-items').not('.a4j-slick-initialized').a4jSlick({
                'prevArrow': '<span data-role="none" class="a4j-slick-prev" aria-label="Previous" tabindex="0" role="button"><i class="ing-icon-angle-left ing-fw"></i></span>',
                'nextArrow': '<span data-role="none" class="a4j-slick-next" aria-label="Next" tabindex="0" role="button"><i class="ing-icon-angle-right ing-fw"></i></span>'
            });
            this.jqNode.off('beforeCarouselChange').on('beforeCarouselChange', function (e, _slick, _prevSlideID, _nextSlideID) {
                self.checkCarouselLoad(_nextSlideID);
            });
        } else if (this.cfg.layout_type.indexOf('masonry') >= 0) {
            this.buildMasonryLayout();
            this.activateMasonry();
        }
        this.bindItemLinks();

        loadMoreBtn.off('click').on('click', function () {
            var $this = $(this);
            if ($this.hasClass('disabled')) {
                return false;
            }
            var btnContent = $this.html();
            self.page++;
            self.jqNode.attr('data-page', self.page);
            var currentPage = self.page;
            var data = 'id=' + self.id + '&page=' + currentPage;
            var messageContainer = self.jqNode.find('.ingallery-loadmore');
            var addData = window.inGallery.addRequestData(self.jqNode);
            if (addData) {
                data += '&' + addData;
            }
            $this.addClass('disabled').html('<i class="ing-icon-spin2 animate-spin"></i>');
            $.ajax({
                'url': window.inGallery.getParam('ajax_url'),
                'type': 'get',
                'dataType': 'json',
                'data': data,
                'success': function (response, statusText, xhr, $form) {
                    if (!response) {
                        messageContainer.html(getMessageHTML(window.inGallery.getLangString('system_error'), 'error'));
                        return false;
                    }
                    if (response.status && response.status == 'success') {
                        var gallery = self.jqNode;
                        var newItems = $(response.html).find('.ingallery-cell');
                        var itemsContainer = gallery.find('.ingallery-items');
                        if (self.cfg.layout_type == 'carousel') {
                            gallery.css('min-height', gallery.height());
                            itemsContainer.a4jSlick('slickAdd', newItems);
                            gallery.css('min-height', 'auto');
                        } else if (self.cfg.layout_type.indexOf('masonry') >= 0) {
                            itemsContainer.append(newItems)
                            self.buildMasonryLayout(gallery);
                            itemsContainer.masonry('appended', newItems);
                        } else {
                            itemsContainer.append(newItems);
                        }
                        self.lazyImages();
                        /*newItems.css('opacity',0).stop().animate({
                         'opacity': 1
                         },300);*/
                        self.bindItemLinks(gallery);
                        var galleryFiltered = gallery.attr('data-filtered');
                        if (galleryFiltered && galleryFiltered != '' && galleryFiltered != '0') {
                            self.filterGallery(galleryFiltered, true);
                        } else {
                            self.fitLayout();
                        }
                        if (response.has_more) {
                            $this.removeClass('disabled').html(btnContent);
                            if (gallery.hasClass('ingallery-layout-carousel')) {
                                self.checkCarouselLoad(null);
                            } else if (infScroll.length) {
                                self.checkInfScroll(loadMoreBtn);
                            }
                        } else {
                            var tmp = gallery.find('.ingallery-loadmore');
                            tmp.slideUp(200, 'swing', function () {
                                tmp.remove();
                            });
                        }
                        $(window).trigger('resize');
                    } else if (response.status && response.status == 'error') {
                        messageContainer.html(getMessageHTML(response.message, 'error'));
                    } else {
                        messageContainer.html(getMessageHTML(window.inGallery.getLangString('system_error'), 'error'));
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    messageContainer.html(getMessageHTML(window.inGallery.getLangString('system_error') + '<br><i>' + textStatus + ' (' + errorThrown + ')</i>', 'error'));
                }
            });

            return false;
        });
        if (this.cfg.layout_type == 'carousel') {
            loadMoreBtn.click();
        } else if (infScroll.length) {
            $(window).on('scroll', function () {
                self.checkInfScroll(loadMoreBtn);
            });
            self.checkInfScroll(loadMoreBtn);
        }

        this.jqNode.off('updateLayout').on('updateLayout', function () {
            self.updateLayout();
        });


        $(window).on('resize', function () {
            self.updateLayout();
        }).trigger('resize');

        window.setInterval(function () {
            self.checkIsVisible();
        }, 100);
    }

    InGallery.prototype = {
        checkIsVisible: function () {
            this.lazyImages();
            var visible = this.jqNode.is(':visible');
            if (!this.visible && visible) {
                this.updateLayout();
            }
            this.visible = visible;
        },
        lazyImages: function(){
            var winY = parseInt($(window).scrollTop()+$(window).height());
            var thisY = this.jqNode.offset().top;
            if((winY+200)>=thisY){
                this.jqNode.find('img').each(function(){
                    var img = $(this);
                    if(img.data('src')){
                        img.attr('src', img.data('src'));
                        img.data('src', null);
                        img.css('opacity', 0);
                        img.on('load', function () {
                            if(this.complete && this.naturalWidth) {
                                img.animate({'opacity': 1}, 300);
                            }
                        });
                    }
                });
            }
        },
        updateLayout: function () {
            if (this.layoutFitTimeout > 0) {
                window.clearTimeout(this.layoutFitTimeout);
                this.layoutFitTimeout = 0;
            }
            this.layoutFitTimeout = window.setTimeout(this.fitLayout.bind(this), 150);
        },
        fitLayout: function () {
            if (this.layoutFitTimeout > 0) {
                window.clearTimeout(this.layoutFitTimeout);
                this.layoutFitTimeout = 0;
            }
            var winWidth = $(window).width();
            var respCfg = {
                "cols": parseInt(this.cfg.layout_cols),
                "rows": parseInt(this.cfg.layout_rows),
                "gutter": parseInt(this.cfg.layout_gutter)
            };
            for (var w in this.cfg.layout_responsive) {
                if (parseInt(w) >= winWidth) {
                    respCfg = this.cfg.layout_responsive[w];
                    break;
                }
            }
            var $margin = (respCfg.gutter > 0 ? Math.floor(respCfg.gutter / 2) : 0);
            var $widthPercent = (respCfg.cols > 1 ? precisionRound(100 / respCfg.cols, 3) : 100);
            if (this.cfg.layout_type == 'masonry') {
                $widthPercent = (respCfg.cols > 1 ? precisionRound(100 / (respCfg.cols + 1), 3) : 100);
            }

            var css = "";
            if (this.cfg.layout_type == 'carousel') {
                css += "#ingallery-" + this.id + " .ingallery-col, #ingallery-" + this.id + " .ingallery-cell{padding:" + $margin + "px;}";
            } else {
                css += "#ingallery-" + this.id + " .ingallery-items{margin-left:-" + $margin + "px;margin-right:-" + $margin + "px;}";
                css += "#ingallery-" + this.id + " .ingallery-col, #ingallery-" + this.id + " .ingallery-cell{padding-left:" + $margin + "px;padding-right:" + $margin + "px;padding-bottom:" + respCfg.gutter + "px;width:" + $widthPercent + "%;}";
                css += "#ingallery-" + this.id + " .grid-sizer{width:" + $widthPercent + "%;}";
                css += "#ingallery-" + this.id + " .ingallery-col-big, #ingallery-" + this.id + " .ingallery-cell-big{width:" + ($widthPercent * 2) + "%;}";
            }

            var customCSSNode = this.jqNode.find('.custom-css-node');
            if (customCSSNode.length == 0) {
                customCSSNode = $('<style type="text/css" class="custom-css-node"></style>');
                this.jqNode.append(customCSSNode);
            }
            customCSSNode.text(css);

            if (this.masonry) {
                var feed = this;
                this.masonry.masonry();
                var images = this.masonry.find('img');
                var imagesLoaded = 0;
                var imagesTotal = images.length;
                var imgLoaded = function (img) {
                    imagesLoaded++;
                    if (imagesLoaded >= imagesTotal) {
                        feed.masonry.masonry('layout');
                    }
                };
                images.each(function () {
                    if (this.complete && this.naturalWidth) {
                        imgLoaded(this);
                    }
                });
                if (imagesLoaded < imagesTotal) {
                    images.on('load', function () {
                        if (this.complete && this.naturalWidth) {
                            imgLoaded(this);
                        }
                    });
                }
            }
            var newImages = this.jqNode.find('.ingallery-item img').not('.ing-loading, .ing-loaded');
            newImages.each(function () {
                if (this.complete && this.naturalWidth) {
                    return
                }
                var $img = $(this);
                $img.addClass('ing-loading').css('opacity', 0);
                $img.on('load', function () {
                    if (this.complete && this.naturalWidth) {
                        $(this).removeClass('ing-loading').addClass('ing-loaded').stop().animate({'opacity': 1}, 300);
                    }
                });
            });
        },
        showInPopup: function (_item) {
            if (popupShown) {
                this.updatePopup(_item);
            } else {
                this.createPopup(_item);
            }
        },

        createPopup: function (_item) {
            if (popupShown) {
                return;
            }
            var gallery = this;
            var popupHTML;
            popupHTML = '<div id="ingallery-shade">';
            popupHTML += '<div id="ingallery-popup" class="' + this.id + '">';
            popupHTML += '<div id="ingallery-popup-close"><i class="ing-icon-cancel-1"></i></div>';
            popupHTML += '<div id="ingallery-popup-wrap">';
            popupHTML += '<div id="ingallery-popup-wrap-img">';
            popupHTML += '<div id="ingallery-popup-left"><i class="ing-icon-angle-left"></i></div>';
            popupHTML += '<div id="ingallery-popup-right"><i class="ing-icon-angle-right"></i></div>';
            popupHTML += '<div id="ingallery-popup-wrap-img-cnt"></div>';
            popupHTML += '</div>';
            popupHTML += '<div id="ingallery-popup-wrap-content"></div>';
            popupHTML += '</div>';
            popupHTML += '</div>';
            popupHTML += '</div>';
            popupShade = $(popupHTML);
            var _body = $('body');
            popup = popupShade.find('#ingallery-popup');
            _body.append(popupShade);
            popupShown = true;
            popupShade.fadeIn(300);
            popup.css('opacity', 0).css('margin-top', '30px').stop().animate({
                opacity: 1,
                'margin-top': 0
            }, 300);

            popupShade.on('click', function (e) {
                var _target = $(e.target);
                var _close = _target.closest('#ingallery-popup-close');
                if (_target.is(popupShade) || _close.length) {
                    gallery.closePopup.call(this);
                }
            });

            $(document).on('keydown', this.processKeydown);

            this.placeDataToPopup(_item);
        },
        updatePopup: function (_item) {
            if (!popupShown) {
                return;
            }
            var gallery = this;
            var popupWraps = $('#ingallery-popup-wrap-img-cnt > img,#ingallery-popup-wrap-content');
            popupWraps.stop().animate({
                'opacity': 0
            }, 100, 'swing', function () {
                gallery.placeDataToPopup(_item);
                popupWraps.stop().animate({
                    'opacity': 1
                }, 100);
            });
        },
        closePopup: function () {
            if (!popupShown) {
                return;
            }
            var _body = $('body');
            popupShade.fadeOut(300, 'swing', function () {
                popupShade.remove();
                popupShown = false;
            });
            popup.stop().animate({
                opacity: 0,
                'margin-top': 30
            }, 300);
            $(document).off('keydown', this.processKeydown);
        },

        processKeydown: function (e) {
            if (!e.which) {
                return;
            }
            if (e.which == 37) {
                $('#ingallery-popup-left:visible').click();
            } else if (e.which == 39) {
                $('#ingallery-popup-right:visible').click();
            }
        },

        placeDataToPopup: function (_item) {
            var popupWrap = $('#ingallery-popup-wrap');
            var popupImgWrap = $('#ingallery-popup-wrap-img');
            var popupContentWrap = $('#ingallery-popup-wrap-content');
            if (popupImgWrap.length == 0 || popupContentWrap.length == 0) {
                return false;
            }
            var gallery = this;
            popupImgWrap.attr('data-img-size', this.cfg.display_popup_img_size);
            popupImgWrap.attr('data-ratio', _item.ratio);
            popupImgWrap.attr('data-width', _item.full_width);
            popupImgWrap.attr('data-height', _item.full_height);
            var imgPlaceholder = popupImgWrap.find('#ingallery-popup-wrap-img-cnt');
            imgPlaceholder.off('click').removeClass('ing-video').removeClass('ing-playing');
            if (_item.is_video) {
                imgPlaceholder.addClass('ing-video');
                var _video = $('<video poster="' + _item.display_src + '" src="' + _item.video_url + '" preload="false" ' + (this.cfg.display_popup_loop_video ? 'loop="loop"' : '') + ' playsinline type="video/mp4"><source src="' + _item.video_url + '" type="video/mp4"></video>');
                imgPlaceholder.empty().append(_video);

                _video.on('click', function () {
                    var player = _video.get(0);
                    if (player.paused) {
                        imgPlaceholder.addClass('ing-playing');
                        player.play();
                    } else {
                        imgPlaceholder.removeClass('ing-playing');
                        player.pause();
                    }
                });
            } else if (_item.subgallery.length) {
                var _subgallery = $('<div id="ingallery-popup-wrap-img-subgallery"></div>');
                var _subgalleryItems = $('<div id="ingallery-popup-wrap-img-subgallery-items"></div>');
                var _subgalleryNav = $('<div id="ingallery-popup-wrap-img-subgallery-nav"></div>');
                var _subgalleryPrev = $('<div id="ingallery-popup-wrap-img-subgallery-prev"><i class="ing-icon-angle-left ing-fw"></i></div>');
                var _subgalleryNext = $('<div id="ingallery-popup-wrap-img-subgallery-next"><i class="ing-icon-angle-right ing-fw"></i></div>');
                var _subgalleryCurrentIdx = 0;
                for (var i = 0; i < _item.subgallery.length; i++) {
                    var _subgalleryItemClass = '';
                    if (i == _subgalleryCurrentIdx) {
                        _subgalleryItemClass = 'active';
                    }
                    var _subgalleryItem = '<div class="ingallery-popup-subgallery-item ' + _subgalleryItemClass + '" data-ratio="' + _item.subgallery[i].ratio + '" data-width="' + _item.subgallery[i].width + '" data-height="' + _item.subgallery[i].height + '">';
                    if (_item.subgallery[i].is_video) {
                        imgPlaceholder.addClass('ing-video');
                        _subgalleryItem += '<video poster="' + _item.subgallery[i].src + '" src="' + _item.subgallery[i].video_url + '" preload="false" ' + (this.cfg.display_popup_loop_video ? 'loop="loop"' : '') + ' playsinline type="video/mp4"><source src="' + _item.subgallery[i].video_url + '" type="video/mp4"></video>';
                    } else {
                        _subgalleryItem += '<img src="' + _item.subgallery[i].src + '" />';
                    }
                    _subgalleryItem += '</div>';
                    _subgalleryItems.append(_subgalleryItem);
                    _subgalleryNav.append('<i class="' + _subgalleryItemClass + '"></i>');
                }
                _subgallery.append(_subgalleryItems);
                _subgallery.append(_subgalleryNav);
                _subgallery.append(_subgalleryPrev);
                _subgallery.append(_subgalleryNext);
                imgPlaceholder.empty().append(_subgallery);

                _subgallery.find('video').each(function () {
                    var _video = $(this);
                    _video.on('click', function () {
                        var player = _video.get(0);
                        if (player.paused) {
                            imgPlaceholder.addClass('ing-playing');
                            player.play();
                        } else {
                            imgPlaceholder.removeClass('ing-playing');
                            player.pause();
                        }
                    });
                });

                var checkNav = function () {
                    if (_subgalleryCurrentIdx == 0) {
                        _subgalleryPrev.hide();
                    } else {
                        _subgalleryPrev.show();
                    }
                    if (_subgalleryCurrentIdx == _item.subgallery.length - 1) {
                        _subgalleryNext.hide();
                    } else {
                        _subgalleryNext.show();
                    }
                }
                checkNav();
                var switchItem = function (_index) {
                    if (_index < 0 || _index > _item.subgallery.length - 1) {
                        return false;
                    }
                    if (imgPlaceholder.hasClass('ing-playing')) {
                        _subgalleryItems.children('.active').find('video').click();
                    }
                    var _direction = (_subgalleryCurrentIdx > _index ? 'backward' : 'forward');
                    var _csgitemEx = $(_subgalleryItems.children().get(_subgalleryCurrentIdx));
                    var _csgitem = $(_subgalleryItems.children().get(_index));

                    _subgalleryCurrentIdx = _index;
                    popupImgWrap.attr('data-ratio', _csgitem.attr('data-ratio'));
                    popupImgWrap.attr('data-width', _csgitem.attr('data-width'));
                    popupImgWrap.attr('data-height', _csgitem.attr('data-height'));
                    _subgalleryItems.children().removeClass('active prev next');
                    _csgitem.addClass('active');
                    _csgitem.prev().addClass('prev');
                    _csgitem.next().addClass('next');
                    _subgalleryNav.children().removeClass('active');
                    $(_subgalleryNav.children().get(_index)).addClass('active');
                    if (_csgitem.find('video').length) {
                        imgPlaceholder.addClass('ing-video');
                    } else {
                        imgPlaceholder.removeClass('ing-video');
                    }
                    checkNav();
                    if (_direction == 'forward') {
                        _csgitemEx.animate({
                            'left': '-100%'
                        }, 100);
                        _csgitem.css('left', '100%').animate({
                            'left': 0
                        }, 100);
                    } else {
                        _csgitemEx.animate({
                            'left': '100%'
                        }, 100);
                        _csgitem.css('left', '-100%').animate({
                            'left': 0
                        }, 100);
                    }

                    fitPopup();
                }
                _subgalleryNav.children().on('click', function () {
                    switchItem($(this).index());
                    return false;
                });
                _subgalleryPrev.on('click', function () {
                    switchItem(_subgalleryCurrentIdx - 1);
                    return false;
                });
                _subgalleryNext.on('click', function () {
                    switchItem(_subgalleryCurrentIdx + 1);
                    return false;
                });
                switchItem(0);
            } else {
                var _img = $('<img />').css('opacity', 0);
                _img.on('load', function () {
                    _img.animate({
                        'opacity': 1
                    }, 200);
                });
                _img.attr('src', _item.display_src);
                imgPlaceholder.empty().append(_img);
            }
            var contentHTML = '';
            if (this.cfg.display_popup_user) {
                contentHTML += '<div class="ingallery-popup-content-user">';
                if (this.cfg.display_popup_instagram_link) {
                    contentHTML += '<a href="https://www.instagram.com/' + _item.owner_username + '/" title="' + _item.owner_name + '" target="_blank">';
                }
                contentHTML += '<img src="' + _item.owner_pic_url + '"> ' + _item.owner_username;
                if (this.cfg.display_popup_instagram_link) {
                    contentHTML += '</a>';
                }
                contentHTML += '</div>';
            }
            if (this.cfg.display_popup_likes || this.cfg.display_popup_comments || this.cfg.display_popup_plays || this.cfg.display_popup_date) {
                contentHTML += '<div class="ingallery-popup-content-stats">';
                if (this.cfg.display_popup_date) {
                    contentHTML += '<time title="' + _item.full_date + '" datetime="' + _item.date_iso + '">' + _item.time_passed + '</time>';
                }
                if (this.cfg.display_popup_likes) {
                    contentHTML += '<span class="ingallery-popup-content-stats-likes"><i class="ing-icon-heart-1"></i> ' + _item.likes + '</span>';
                }
                if (this.cfg.display_popup_comments) {
                    contentHTML += '<span class="ingallery-popup-content-stats-comments"><i class="ing-icon-comment-1"></i> ' + _item.comments + '</span>';
                }
                contentHTML += '</div>';
            }
            if (this.cfg.display_popup_description || this.cfg.display_popup_comments_list) {
                contentHTML += '<div class="ingallery-popup-content-stretch">';
            }
            if (this.cfg.display_popup_description) {
                contentHTML += '<div class="ingallery-popup-content-descr">' + _item.caption + '</div>';
            }
            var needToLoadComments = false;
            if (this.cfg.display_popup_comments_list) {
                needToLoadComments = true;
                contentHTML += '<div class="ingallery-popup-content-comments ing-' + _item.code + ' ing-loading">';
                if (this.commentsCache[_item.code]) {
                    needToLoadComments = false;
                    contentHTML += this.commentsCache[_item.code];
                }
                contentHTML += '</div>';
            }
            if (this.cfg.display_popup_description || this.cfg.display_popup_comments_list) {
                contentHTML += '</div>';
            }

            if (contentHTML != '') {
                popupWrap.removeClass('noRight');
                popupContentWrap.html(contentHTML);
            } else {
                popupWrap.addClass('noRight');
            }


            if (needToLoadComments) {
                $.ajax({
                    url: window.inGallery.getParam('ajax_url'),
                    type: 'get',
                    dataType: 'json',
                    cache: true,
                    data: 'task=gallery.comments&media_code=' + _item.code,
                    success: function (response, statusText, xhr, $form) {
                        if (response && response.status && response.status == 'success') {
                            gallery.commentsCache[response.media_code] = response.html;
                            var comments = $(response.html);
                            popupContentWrap.find('.ingallery-popup-content-comments.ing-' + response.media_code).removeClass('ing-loading').append(comments);
                            comments.css('opacity', 0).stop().animate({
                                'opacity': 1
                            }, 300);
                        }
                    },
                });
            }

            var _items = this.jqNode.find('a.ingallery-item-link-popup:visible');
            var goPrev = $('#ingallery-popup-left');
            var goNext = $('#ingallery-popup-right');
            var itemIndex = _items.index(_item.jqItem);
            if (itemIndex > 0) {
                goPrev.show();
                goPrev.off('click').on('click', function () {
                    _items.get(itemIndex - 1).click();
                });
            } else {
                goPrev.hide();
            }
            if (itemIndex + 1 < _items.length) {
                goNext.show();
                goNext.off('click').on('click', function () {
                    _items.get(itemIndex + 1).click();
                });
            } else {
                goNext.hide();
            }

            if (itemIndex > _items.length - 4) {
                this.jqNode.find('.ingallery-loadmore-btn').click();
            }

            fitPopup();

            return true;
        },

        filterGallery: function (albumID, _force) {
            this.jqNode.css('height', this.jqNode.height());
            var oldAlbum = this.jqNode.find('.ingallery-album.active');
            if (oldAlbum.attr('data-id') == albumID && !_force) {
                return;
            }
            var newAlbum = this.jqNode.find('.ingallery-album[data-id="' + albumID + '"]');
            var itemsToShow;
            var itemsToHide;

            this.jqNode.attr('data-filtered', newAlbum.attr('data-id'));
            oldAlbum.removeClass('active');
            newAlbum.addClass('active');

            itemsToHide = this.jqNode.find('.ingallery-cell');

            if (newAlbum.attr('data-id') == '0') {
                itemsToShow = this.jqNode.find('.ingallery-cell');
            } else {
                itemsToShow = this.jqNode.find('.ingallery-cell[data-album="' + newAlbum.attr('data-id') + '"]');
            }
            itemsToHide.stop().hide();
            itemsToShow.stop().show().css('opacity', 0).animate({
                'opacity': 1
            }, 300);

            this.jqNode.css('height', 'auto');
            this.fitLayout();

            if (this.masonry) {
                this.buildMasonryLayout();
                this.masonry.masonry('layout');
            }
        },

        checkCarouselLoad: function (_nextSlideID) {
            var carousel = this.jqNode.find('.ingallery-items').data('ingslick');
            if (!carousel) {
                return;
            }
            var curSlide;
            var totalSlides;
            if (_nextSlideID) {
                carousel.currentSlide = _nextSlideID;
            }
            if (carousel.options.rows > 1) {
                curSlide = carousel.currentSlide;
                totalSlides = carousel.slideCount;
                //carousel.options.slidesPerRow
            } else {
                curSlide = Math.floor((parseInt(carousel.currentSlide) + 1) / parseInt(carousel.options.slidesToShow));
                totalSlides = Math.ceil((parseInt(carousel.slideCount) - parseInt(carousel.options.slidesToShow)) / parseInt(carousel.options.slidesToShow));
            }
            if (curSlide > totalSlides - 3) {
                this.jqNode.find('.ingallery-loadmore-btn').click();
            }
        },

        checkInfScroll: function (loadMoreBtn) {
            if (loadMoreBtn.hasClass('disabled')) {
                return false;
            }
            var top = $(window).scrollTop() + ($(window).height() * 1.4);
            if (top > loadMoreBtn.offset().top) {
                loadMoreBtn.click();
            }
        },

        buildMasonryLayout: function () {
            if (this.cfg.layout_type != 'masonry') {
                return this;
            }
            var cols = this.cfg.layout_cols;
            var maxColPos = cols - 1;
            var curColPos = -1;
            var colPosDir = 1;
            var lastRowNum = -1;

            this.jqNode.find('.ingallery-cell:visible').each(function (index) {
                var itemRowNum = Math.floor(index / (cols * 2 - 1));
                var itemColNum = index % (cols * 2 - 1);
                if (lastRowNum != itemRowNum) {
                    if (curColPos + colPosDir > maxColPos) {
                        colPosDir = -1;
                    } else if (curColPos + colPosDir < 0) {
                        colPosDir = 1;
                    }
                    curColPos += colPosDir;
                }
                if (curColPos == itemColNum) {
                    $(this).addClass('ingallery-cell-big');
                } else {
                    $(this).removeClass('ingallery-cell-big');
                }
                lastRowNum = itemRowNum;
            });
            this.fitLayout();
        },

        activateMasonry: function () {
            this.masonry = this.jqNode.find('.ingallery-items').masonry({
                'itemSelector': '.ingallery-cell',
                'percentPosition': true,
                'columnWidth': '.grid-sizer',
                'masonry': {
                    'columnWidth': '.grid-sizer'
                }
            });
        },

        bindItemLinks: function () {
            var gallery = this;
            this.jqNode.find('a.ingallery-item-link-popup').off('click').on('click', function (e) {
                e.preventDefault();
                var $this = $(this);
                var itemData = $this.attr('data-item');
                if (!itemData) {
                    return false;
                }
                var _item = JSON.parse(itemData);
                _item.jqItem = $this;
                gallery.showInPopup(_item);
                return false;
            });
        },

    }

    function getMessageHTML(msg, type) {
        var html = '<div class="ingallery-message ing-' + type + '">';
        html += '<div class="ingallery-message-title">' + window.inGallery.getLangString('error_title') + '</div>';
        html += '<div class="ingallery-message-text">' + msg + '</div>';
        html += '</div>';
        return html;
    }

    function setFitPopup() {
        if (fitPopupTimeout > 0) {
            window.clearTimeout(fitPopupTimeout);
            fitPopupTimeout = 0;
        }
        fitPopupTimeout = window.setTimeout(fitPopup, 100);
    }

    function fitPopup() {
        if (fitPopupTimeout > 0) {
            window.clearTimeout(fitPopupTimeout);
            fitPopupTimeout = 0;
        }
        if (!popupShown) {
            return;
        }
        var popupImgWrap = $('#ingallery-popup-wrap-img');
        var popupWrap = $('#ingallery-popup-wrap');
        var winH = $(window).height();
        var winW = $(window).width();
        var ratio = popupImgWrap.attr('data-ratio');
        if (popupImgWrap.length == 0 || popupWrap.length == 0) {
            return false;
        }
        if (winW < 750) {
            winW = $('#ingallery-popup').width();
            popup.addClass('ing-smallest');
            popupWrap.height(winW / ratio);
            return false;
        } else {
            popup.removeClass('ing-smallest');
        }
        var maxH = winH - 100;
        var minH = 550;
        var maxw = winW - 330 - 100;
        if (popupImgWrap.attr('data-img-size') == 'try_to_fit') {
            maxH = Math.min(maxH, popupImgWrap.attr('data-height'));
            maxw = Math.min(maxw, popupImgWrap.attr('data-width'));
        }
        var width = 750;
        var height = width / ratio;
        if (height > maxH) {
            height = maxH;
            width = height * ratio;
        }
        if (height < minH) {
            height = minH;
            width = height * ratio;
        }
        if (width > maxw) {
            width = maxw;
            height = width / ratio;
        }
        var top = Math.max((winH - height) / 2, 50);
        popup.css('top', top);
        popupWrap.height(height);
        popupImgWrap.width(width);

    }

    $(document).ready(function (e) {
        $('.ingallery-container').each(function () {
            var self = $(this);
            self.html(loaderHTML);
            var $aj = {
                url: window.inGallery.getParam('ajax_url'),
                type: 'get',
                dataType: 'json',
                cache: true,
                data: 'task=gallery.view&id=' + self.attr('data-id'),
                success: function (response, statusText, xhr, $form) {
                    if (!response) {
                        self.html(getMessageHTML(window.inGallery.getLangString('system_error'), 'error'));
                        return false;
                    }
                    if (response && response.status && response.status == 'success') {
                        var node = $(response.html);
                        self.replaceWith(node);
                        new InGallery(node.get(0));
                    } else if (response && response.status && response.status == 'error') {
                        self.html(getMessageHTML(response.message, 'error'));
                    } else {
                        self.html(getMessageHTML(window.inGallery.getLangString('system_error'), 'error'));
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    self.html(getMessageHTML(window.inGallery.getLangString('system_error') + '<br><i>' + textStatus + ' (' + errorThrown + ')</i>', 'error'));
                },
            }
            $.ajax($aj);
        });
    });

    function precisionRound(number, precision) {
        var factor = Math.pow(10, precision);
        return Math.round(number * factor) / factor;
    }

    $(window).on('resize', function () {
        setFitPopup();
    });

    window.inGallery = {
        config: null,
        reInit: function () {
            $('.ingallery').each(function () {
                new InGallery(this);
            });
        },
        addRequestData: function (gallery) {
            return '';
        },
        getParams: function () {
            if (this.config !== null) {
                return this.config;
            } else if ($('#ingallery-cfg').length) {
                this.config = JSON.parse($('#ingallery-cfg').html());
                if (!this.config || !this.config['ajax_url']) {
                    console.log('No InGallery config');
                }
                return this.config;
            } else {
                console.log('No InGallery config');
            }
        },
        getLangString: function (key) {
            var params = this.getParams();
            return params.lang[key];
        },
        getParam: function (paramName) {
            var params = this.getParams();
            return params[paramName];
        },
    }

})(jQuery);

(function ($) {
    'use strict';

    var wrap,
            cfg,
            html,
            albumTmpl,
            currentPageID = '',
            currentScreen,
            screensBlock,
            appWidth,
            updatePreviewTO = 0;

    var loaderHTML = '<div class="ingLoaderWrap"><i class="ing-icon-spin2 animate-spin"></i></div>';

    function init() {
        wrap = $('#ingalleryApp');
        if (wrap.length == 0) {
            return false;
        }
        cfg = JSON.parse(wrap.attr('data-ingallery'));

        appWidth = wrap.width();

        wrap.append('<div class="ingallery-app-head"><strong>InGallery</strong> AllForJoomla.com</div>');

        html = '<div class="ingallery-app-menu">';
        html += '<a href="#" data-href="#ingallery-albums">' + window.inGallery.getParams().lang.albums + '</a>';
        html += '<a href="#" data-href="#ingallery-layout">' + window.inGallery.getParams().lang.layout + '</a>';
        html += '<a href="#" data-href="#ingallery-display">' + window.inGallery.getParams().lang.display + '</a>';
        html += '<a href="#" data-href="#ingallery-colors">' + window.inGallery.getParams().lang.colors + '</a>';
        html += '</div>';
        wrap.append(html);

        html = '<div class="ingallery-app-screens">';
        html += '<div class="ingallery-app-screens-wrap">';
// albums
        html += '<div class="ingallery-app-screen" id="ingallery-albums"><div class="ingallery-app-albums-wrap"></div>';
        html += '<a href="#" class="ingallery-app-albums-add-btn" title="' + window.inGallery.getParams().lang.add_album + '"></a>';
        html += '</div>';
// layout
        html += '<div class="ingallery-app-screen" id="ingallery-layout">';
        html += '<div class="ing-form-row"><label>' + window.inGallery.getParams().lang.type + '</label>';
        html += '<select name="gallery[cfg][layout_type]" class="ing-form-control ingParam-cfg-layout_type">';
        html += '<option value="grid">' + window.inGallery.getParams().lang.grid + '</option>';
        html += '<option value="carousel">' + window.inGallery.getParams().lang.carousel + '</option>';
        html += '<option value="masonry">' + window.inGallery.getParams().lang.masonry + '</option>';
        html += '<option value="masonrycols">' + window.inGallery.getParams().lang.masonrycols + '</option>';
        html += '</select>';
        html += '</div>';
        html += '<div class="ing-form-row"><label>' + window.inGallery.getParams().lang.cols + '</label><input type="number" name="gallery[cfg][layout_cols]" class="ing-form-stepper ingParam-cfg-layout_cols" value="3" min="1" /></div>';
        html += '<div class="ing-form-row"><label>' + window.inGallery.getParams().lang.rows + '</label><input type="number" name="gallery[cfg][layout_rows]" class="ing-form-stepper ingParam-cfg-layout_rows" value="4" min="1" /></div>';
        html += '<div class="ing-form-row"><label>' + window.inGallery.getParams().lang.gutter + '</label><input type="number" name="gallery[cfg][layout_gutter]" class="ing-form-stepper ingParam-cfg-layout_gutter" value="30" min="0" />';
        html += '<i class="ing-helptext">' + window.inGallery.getParams().lang.size_in_px + '</i></div>';

        html += '<div class="ing-form-row responsive-config">';
        html += '<label>' + window.inGallery.getParams().lang.mobile_optimization + '</label>';
        html += '<div id="ingallery-responsive-items"></div>';
        html += '<div id="ingallery-responsive-add">+ ' + window.inGallery.getParams().lang.add + '</div>';
        html += '</div>';

        html += '<div class="ing-form-row" data-depends="layout_autoscroll=1;layout_type=carousel">';
        html += '<label for="layout_autoscroll_speed"> ' + window.inGallery.getParams().lang.autoscroll_speed + '</label>';
        html += '<input type="number" name="gallery[cfg][layout_autoscroll_speed]" class="ing-form-stepper ingParam-cfg-layout_autoscroll_speed" value="2" min="1" />';
        html += '<i class="ing-helptext">' + window.inGallery.getParams().lang.autoscroll_speed_sec + '</i>';
        html += '</div>';
        html += '<div class="ing-form-row" data-depends="layout_type=carousel">';
        html += '<input type="checkbox" id="layout_autoscroll" class="ing-form-checkbox ingParam-cfg-layout_autoscroll" name="gallery[cfg][layout_autoscroll]" value="1" />';
        html += '<label for="layout_autoscroll"> ' + window.inGallery.getParams().lang.autoscroll + '</label>';
        html += '</div>';
        html += '<div class="ing-form-row">';
        html += '<input type="checkbox" id="layout_show_loadmore" class="ing-form-checkbox ingParam-cfg-layout_show_loadmore" name="gallery[cfg][layout_show_loadmore]" value="1" />';
        html += '<label for="layout_show_loadmore"> ' + window.inGallery.getParams().lang.show_loadmore + '</label>';
        html += '</div>';
        html += '<div class="ing-form-row">';
        html += '<input type="checkbox" id="layout_infinite_scroll" class="ing-form-checkbox ingParam-cfg-layout_infinite_scroll" name="gallery[cfg][layout_infinite_scroll]" value="1" />';
        html += '<label for="layout_infinite_scroll"> ' + window.inGallery.getParams().lang.infinite_scroll + '</label>';
        html += '</div>';
        /*html+= '<div class="ing-form-row" data-depends="layout_type=carousel|grid">';
         html+= '<input type="checkbox" id="layout_local_responsiveness" class="ing-form-checkbox ingParam-cfg-layout_local_responsiveness" name="gallery[cfg][layout_local_responsiveness]" value="1" />';
         html+= '<label for="layout_local_responsiveness"> '+window.inGallery.getParams().lang.local_responsiveness+'</label>';
         html+= '</div>';*/
        html += '</div>';
// style
        html += '<div class="ingallery-app-screen" id="ingallery-display">';
        html += '<div class="ing-form-row"><label>' + window.inGallery.getParams().lang.style + '</label><select name="gallery[cfg][display_style]" class="ing-form-control ingParam-cfg-display_style">';
        html += '<option>default</option>';
        html += '<option>flipcards</option>';
        html += '<option>circles</option>';
        html += '<option>circles2</option>';
        html += '<option>dribbble</option>';
        html += '<option>grayscale</option>';
        html += '<option>card</option>';
        html += '</select></div>';
        html += '<div class="ing-form-row"><label>' + window.inGallery.getParams().lang.display_mode + '</label><select name="gallery[cfg][display_link_mode]" class="ing-form-control ingParam-cfg-display_link_mode">';
        html += '<option value="popup">' + window.inGallery.getParams().lang.show_in_popup + '</option><option value="link">' + window.inGallery.getParams().lang.link_to_instagram + '</option>';
        html += '</select></div>';
        html += '<div class="ing-form-row"><label>' + window.inGallery.getParams().lang.popup_img_size + '</label><select name="gallery[cfg][display_popup_img_size]" class="ing-form-control ingParam-cfg-display_popup_img_size">';
        html += '<option value="try_to_fit">' + window.inGallery.getParams().lang.try_to_fit + '</option><option value="full_size">' + window.inGallery.getParams().lang.full_size + '</option>';
        html += '</select>';
        html += '<i class="ing-helptext">' + window.inGallery.getParams().lang.popup_img_size_help + '</i>';
        html += '</div>';
        html += '<div class="ing-form-row">';
        html += '<input type="checkbox" id="layout_show_albums" class="ing-form-checkbox ingParam-cfg-layout_show_albums" name="gallery[cfg][layout_show_albums]" value="1" />';
        html += '<label for="layout_show_albums"> ' + window.inGallery.getParams().lang.show_albums + '</label>';
        html += '</div>';

        html += '<div class="ing-form-row"><label>' + window.inGallery.getParams().lang.loadmore_text + '</label><input type="text" name="gallery[cfg][layout_loadmore_text]" class="ing-form-control ingParam-cfg-layout_loadmore_text" maxlength="50" /></div>';

        html += '<div class="ing-form-row">';
        html += '<label>' + window.inGallery.getParams().lang.img_preloader + '</label>';
        html += '<input type="text" name="gallery[cfg][layout_preloader_img]" class="ing-form-control ingParam-cfg-layout_preloader_img" />';
        html += '<i class="ing-helptext">' + window.inGallery.getParams().lang.img_preloader_descr + '</i>';
        html += '</div>';

        html += '<div class="ingallery-app-subheader">' + window.inGallery.getParams().lang.display_on_thumbs + '</div>';
        html += '<div class="ing-form-row" data-depends="display_style=card">';
        html += '<input type="checkbox" id="display_thumbs_header" class="ing-form-checkbox ingParam-cfg-display_thumbs_header" name="gallery[cfg][display_thumbs_header]" value="1" />';
        html += '<label for="display_thumbs_header"> ' + window.inGallery.getParams().lang.header + '</label>';
        html += '</div>';
        html += '<div class="ing-form-row" data-depends="display_style=card">';
        html += '<input type="checkbox" id="display_thumbs_instalink" class="ing-form-checkbox ingParam-cfg-display_thumbs_instalink" name="gallery[cfg][display_thumbs_instalink]" value="1" />';
        html += '<label for="display_thumbs_instalink"> ' + window.inGallery.getParams().lang.link_to_instagram + '</label>';
        html += '</div>';
        html += '<div class="ing-form-row">';
        html += '<input type="checkbox" id="display_thumbs_likes" class="ing-form-checkbox ingParam-cfg-display_thumbs_likes" name="gallery[cfg][display_thumbs_likes]" value="1" />';
        html += '<label for="display_thumbs_likes"> ' + window.inGallery.getParams().lang.likes + '</label>';
        html += '</div>';
        html += '<div class="ing-form-row">';
        html += '<input type="checkbox" id="display_thumbs_comments" class="ing-form-checkbox ingParam-cfg-display_thumbs_comments" name="gallery[cfg][display_thumbs_comments]" value="1" />';
        html += '<label for="display_thumbs_comments"> ' + window.inGallery.getParams().lang.comments + '</label>';
        html += '</div>';
        html += '<div class="ing-form-row" data-depends="display_style=card">';
        html += '<input type="checkbox" id="display_thumbs_date" class="ing-form-checkbox ingParam-cfg-display_thumbs_date" name="gallery[cfg][display_thumbs_date]" value="1" />';
        html += '<label for="display_thumbs_date"> ' + window.inGallery.getParams().lang.created_date + '</label>';
        html += '</div>';
        html += '<div class="ing-form-row">';
        html += '<input type="checkbox" id="display_thumbs_description" class="ing-form-checkbox ingParam-cfg-display_thumbs_description" name="gallery[cfg][display_thumbs_description]" value="1" />';
        html += '<label for="display_thumbs_description"> ' + window.inGallery.getParams().lang.description + '</label>';
        html += '</div>';
        /*html+= '<div class="ing-form-row">';
         html+= '<input type="checkbox" id="display_thumbs_plays" class="ing-form-checkbox ingParam-cfg-display_thumbs_plays" name="gallery[cfg][display_thumbs_plays]" value="1" />';
         html+= '<label for="display_thumbs_plays"> '+window.inGallery.getParams().lang.video_plays+'</label>';
         html+= '</div>';*/
        html += '<div class="ingallery-app-subheader">' + window.inGallery.getParams().lang.display_in_popup + '</div>';


        html += '<div class="ing-form-row">';
        html += '<input type="checkbox" id="display_popup_user" class="ing-form-checkbox ingParam-cfg-display_popup_user" name="gallery[cfg][display_popup_user]" value="1" />';
        html += '<label for="display_popup_user"> ' + window.inGallery.getParams().lang.user_block + '</label>';
        html += '</div>';
        html += '<div class="ing-form-row">';
        html += '<input type="checkbox" id="display_popup_instagram_link" class="ing-form-checkbox ingParam-cfg-display_popup_instagram_link" name="gallery[cfg][display_popup_instagram_link]" value="1" />';
        html += '<label for="display_popup_instagram_link"> ' + window.inGallery.getParams().lang.link_user_to_instagram + '</label>';
        html += '</div>';
        html += '<div class="ing-form-row">';
        html += '<input type="checkbox" id="display_popup_likes" class="ing-form-checkbox ingParam-cfg-display_popup_likes" name="gallery[cfg][display_popup_likes]" value="1" />';
        html += '<label for="display_popup_likes"> ' + window.inGallery.getParams().lang.likes + '</label>';
        html += '</div>';
        html += '<div class="ing-form-row">';
        html += '<input type="checkbox" id="display_popup_comments" class="ing-form-checkbox ingParam-cfg-display_popup_comments" name="gallery[cfg][display_popup_comments]" value="1" />';
        html += '<label for="display_popup_comments"> ' + window.inGallery.getParams().lang.comments + '</label>';
        html += '</div>';
        /*html+= '<div class="ing-form-row">';
         html+= '<input type="checkbox" id="display_popup_plays" class="ing-form-checkbox ingParam-cfg-display_popup_plays" name="gallery[cfg][display_popup_plays]" value="1" />';
         html+= '<label for="display_popup_plays"> '+window.inGallery.getParams().lang.video_plays+'</label>';
         html+= '</div>';*/
        html += '<div class="ing-form-row">';
        html += '<input type="checkbox" id="display_popup_date" class="ing-form-checkbox ingParam-cfg-display_popup_date" name="gallery[cfg][display_popup_date]" value="1" />';
        html += '<label for="display_popup_date"> ' + window.inGallery.getParams().lang.created_date + '</label>';
        html += '</div>';
        html += '<div class="ing-form-row">';
        html += '<input type="checkbox" id="display_popup_description" class="ing-form-checkbox ingParam-cfg-display_popup_description" name="gallery[cfg][display_popup_description]" value="1" />';
        html += '<label for="display_popup_description"> ' + window.inGallery.getParams().lang.description + '</label>';
        html += '</div>';
        html += '<div class="ing-form-row">';
        html += '<input type="checkbox" id="display_popup_comments_list" class="ing-form-checkbox ingParam-cfg-display_popup_comments_list" name="gallery[cfg][display_popup_comments_list]" value="1" />';
        html += '<label for="display_popup_comments_list"> ' + window.inGallery.getParams().lang.comments_list + '</label>';
        html += '</div>';
        html += '<div class="ing-form-row">';
        html += '<input type="checkbox" id="display_popup_loop_video" class="ing-form-checkbox ingParam-cfg-display_popup_loop_video" name="gallery[cfg][display_popup_loop_video]" value="1" />';
        html += '<label for="display_popup_loop_video"> ' + window.inGallery.getParams().lang.loop_video + '</label>';
        html += '</div>';
        html += '</div>';
// colors
        html += '<div class="ingallery-app-screen" id="ingallery-colors">';
        html += '<div class="ing-form-row">';
        html += '<label>' + window.inGallery.getParams().lang.gallery_bg + '</label>';
        html += '<input type="text" name="gallery[cfg][colors_gallery_bg]" class="ing-form-control ingParam-cfg-colors_gallery_bg ing-form-colorpicker" maxlength="150" />';
        html += '</div>';
        html += '<div class="ingallery-app-subheader">' + window.inGallery.getParams().lang.album_btns_colors + '</div>';
        html += '<div class="ing-form-row">';
        html += '<label>' + window.inGallery.getParams().lang.album_btn_bg + '</label>';
        html += '<input type="text" name="gallery[cfg][colors_album_btn_bg]" class="ing-form-control ingParam-cfg-colors_album_btn_bg ing-form-colorpicker" maxlength="150" />';
        html += '</div>';
        html += '<div class="ing-form-row">';
        html += '<label>' + window.inGallery.getParams().lang.album_btn_text + '</label>';
        html += '<input type="text" name="gallery[cfg][colors_album_btn_text]" class="ing-form-control ingParam-cfg-colors_album_btn_text ing-form-colorpicker" maxlength="150" />';
        html += '</div>';
        html += '<div class="ing-form-row">';
        html += '<label>' + window.inGallery.getParams().lang.album_btn_hover_bg + '</label>';
        html += '<input type="text" name="gallery[cfg][colors_album_btn_hover_bg]" class="ing-form-control ingParam-cfg-colors_album_btn_hover_bg ing-form-colorpicker" maxlength="150" />';
        html += '</div>';
        html += '<div class="ing-form-row">';
        html += '<label>' + window.inGallery.getParams().lang.album_btn_hover_text + '</label>';
        html += '<input type="text" name="gallery[cfg][colors_album_btn_hover_text]" class="ing-form-control ingParam-cfg-colors_album_btn_hover_text ing-form-colorpicker" maxlength="150" />';
        html += '</div>';
        html += '<div class="ingallery-app-subheader">' + window.inGallery.getParams().lang.more_btn_colors + '</div>';
        html += '<div class="ing-form-row">';
        html += '<label>' + window.inGallery.getParams().lang.more_btn_bg + '</label>';
        html += '<input type="text" name="gallery[cfg][colors_more_btn_bg]" class="ing-form-control ingParam-cfg-colors_more_btn_bg ing-form-colorpicker" maxlength="150" />';
        html += '</div>';
        html += '<div class="ing-form-row">';
        html += '<label>' + window.inGallery.getParams().lang.more_btn_text + '</label>';
        html += '<input type="text" name="gallery[cfg][colors_more_btn_text]" class="ing-form-control ingParam-cfg-colors_more_btn_text ing-form-colorpicker" maxlength="150" />';
        html += '</div>';
        html += '<div class="ing-form-row">';
        html += '<label>' + window.inGallery.getParams().lang.more_btn_hover_bg + '</label>';
        html += '<input type="text" name="gallery[cfg][colors_more_btn_hover_bg]" class="ing-form-control ingParam-cfg-colors_more_btn_hover_bg ing-form-colorpicker" maxlength="150" />';
        html += '</div>';
        html += '<div class="ing-form-row">';
        html += '<label>' + window.inGallery.getParams().lang.more_btn_hover_text + '</label>';
        html += '<input type="text" name="gallery[cfg][colors_more_btn_hover_text]" class="ing-form-control ingParam-cfg-colors_more_btn_hover_text ing-form-colorpicker" maxlength="150" />';
        html += '</div>';
        html += '<div class="ingallery-app-subheader">' + window.inGallery.getParams().lang.thumbs_colors + '</div>';

        html += '<div class="ing-form-row">';
        html += '<label>' + window.inGallery.getParams().lang.thumb_overlay_bg + '</label>';
        html += '<input type="text" name="gallery[cfg][colors_thumb_overlay_bg]" class="ing-form-control ingParam-cfg-colors_thumb_overlay_bg ing-form-colorpicker" maxlength="150" />';
        html += '</div>';
        html += '<div class="ing-form-row">';
        html += '<label>' + window.inGallery.getParams().lang.thumb_overlay_text + '</label>';
        html += '<input type="text" name="gallery[cfg][colors_thumb_overlay_text]" class="ing-form-control ingParam-cfg-colors_thumb_overlay_text ing-form-colorpicker" maxlength="150" />';
        html += '</div>';

        html += '</div>';
        html += '</div>';
        html += '</div>';


        wrap.append(html);
        screensBlock = wrap.find('.ingallery-app-screens');


        albumTmpl = '<div class="ingallery-app-album" data-id="{id}">';
        albumTmpl += '<div class="ingallery-app-album-title">';
        albumTmpl += '<span>{title}</span>';
        albumTmpl += '<a href="#" class="ingallery-app-album-moreless-btn"><i class="ing-icon-"></i></a>';
        albumTmpl += '<a href="#" class="ing-del-btn ingallery-app-album-del"><i class="ing-icon-cancel-1"></i></a>';
        albumTmpl += '</div>';
        albumTmpl += '<div class="ingallery-app-album-contents">';
        albumTmpl += '<div class="ing-form-row"><label>' + window.inGallery.getParams().lang.album_name + '</label><input type="text" name="gallery[albums][{id}][name]" class="ing-form-control ingallery-app-album-name" maxlength="50" /></div>';
        albumTmpl += '<div class="ing-form-row"><label for="asrc_{id}">' + window.inGallery.getParams().lang.sources + '</label><input type="text" name="gallery[albums][{id}][sources]" class="ing-form-tokenfield ingallery-app-album-sources" id="asrc_{id}" />';
        albumTmpl += '<i class="ing-helptext">' + window.inGallery.getParams().lang.sources_help + '</i></div>';
        albumTmpl += '<div class="ing-form-row"><label>' + window.inGallery.getParams().lang.filters + '</label>';
        albumTmpl += '<div class="ingallery-app-album-filters">';
        albumTmpl += '<div class="ingallery-app-album-filter">';
        albumTmpl += '<div class="ing-row">';
        albumTmpl += '<div class="ing-col"><b>' + window.inGallery.getParams().lang.only + '</b></div>';
        albumTmpl += '<div class="ing-col"><input type="text" name="gallery[albums][{id}][filters][only]" class="ing-form-tokenfield ingallery-app-album-filters_only" /></div>';
        albumTmpl += '</div>';
        albumTmpl += '</div>';
        albumTmpl += '<div class="ingallery-app-album-filter">';
        albumTmpl += '<div class="ing-row">';
        albumTmpl += '<div class="ing-col"><b>' + window.inGallery.getParams().lang.except + '</b></div>';
        albumTmpl += '<div class="ing-col"><input type="text" name="gallery[albums][{id}][filters][except]" class="ing-form-tokenfield ingallery-app-album-filters_except" /></div>';
        albumTmpl += '</div>';
        albumTmpl += '</div>';
        albumTmpl += '</div>';
        albumTmpl += '<i class="ing-helptext">' + window.inGallery.getParams().lang.filters_help + '</i>';
        albumTmpl += '</div>';
        albumTmpl += '<div class="ing-form-row"><label>' + window.inGallery.getParams().lang.limit + '</label><input type="number" name="gallery[albums][{id}][limit_items]" class="ing-form-stepper ingallery-app-album-limit_items" value="0" min="0" />';
        albumTmpl += '<i class="ing-helptext">' + window.inGallery.getParams().lang.limit_help + '</i></div>';
        albumTmpl += '<div class="ing-form-row"><label>' + window.inGallery.getParams().lang.cache_lifetime + '</label>';
        albumTmpl += '<input type="number" name="gallery[albums][{id}][cache_lifetime]" class="ing-form-stepper ingallery-app-album-cache_lifetime" value="36000" min="0" />';
        albumTmpl += '<i class="ing-helptext">' + window.inGallery.getParams().lang.cache_lifetime_help + '</i></div>';
        albumTmpl += '</div>';
        albumTmpl += '</div>';


        for (var k in cfg.cfg) {
            var _control = wrap.find('.ingParam-cfg-' + k);
            if (_control.is('input[type="checkbox"]')) {
                _control.prop('checked', cfg.cfg[k]);
            } else {
                _control.val(cfg.cfg[k]);
            }
        }
        for (var i = 0; i < cfg.albums.length; i++) {
            addAlbum(cfg.albums[i]);
        }


        //wrap.find('.ingallery-app-screen').css('width',appWidth);

        wrap.find('.ingallery-app-menu a').on('click', function () {
            switchPage($(this).attr('data-href'));
            return false;
        });

        wrap.find('a.ingallery-app-albums-add-btn').on('click', function () {
            addAlbum();
            expandCollapseAlbum(wrap.find('.ingallery-app-album').last());
            return false;
        });

        var respItemsContainer = wrap.find('#ingallery-responsive-items');
        wrap.find('#ingallery-responsive-add').on('click', function () {
            var control = getResponsiveItemControl(0, 3, 4, 10);
            respItemsContainer.append(control);
            controlChanged(null);
            createInputs();
            boxSlideIn(control);
            return false;
        });
        for (var k in cfg.cfg.layout_responsive) {
            respItemsContainer.append(getResponsiveItemControl(k, cfg.cfg.layout_responsive[k].cols, cfg.cfg.layout_responsive[k].rows, cfg.cfg.layout_responsive[k].gutter));
        }

        createInputs();

        $(document).on('click', 'a.ingallery-app-responsive-del', function () {
            var item = $(this).closest('.ingallery-responsive-item');
            boxSlideOut(item, function () {
                this.remove();
            }.bind(item));
            return false;
        });

        $(document).on('click', 'a.ingallery-app-album-moreless-btn', function () {
            expandCollapseAlbum($(this).closest('.ingallery-app-album'));
            return false;
        });
        $(document).on('click', 'a.ingallery-app-album-del', function () {
            removeAlbum($(this).closest('.ingallery-app-album'));
            return false;
        });

        wrap.find('input,select').on('change', function (e) {
            controlChanged(e);
            updatePreview();
        });
        controlChanged(null);

        window.setTimeout(function () {
            switchPage('#ingallery-albums');
            expandCollapseAlbum(wrap.find('.ingallery-app-album').first());
            window.setInterval(function () {
                if (!currentScreen) {
                    return;
                }
                var curHeight = screensBlock.attr('data-height');
                var screenHeight = currentScreen.outerHeight();
                if (!curHeight || curHeight != screenHeight) {
                    screensBlock.attr('data-height', screenHeight);
                    screensBlock.css('height', screenHeight);
                }
            }, 200);
        }, 200);
        if (wrap.attr('data-id') && parseInt(wrap.attr('data-id')) > 0) {
            loadData();
        }
    }

    function expandCollapseAlbum(_album) {
        var _albumContents = _album.find('.ingallery-app-album-contents');
        if (_album.hasClass('active')) {
            _album.removeClass('active');
            _albumContents.stop().slideUp(300);
        } else {
            wrap.find('.ingallery-app-album').removeClass('active').find('.ingallery-app-album-contents').stop().slideUp(300);
            _album.addClass('active');
            _albumContents.stop().slideDown(300);
        }
    }

    function addAlbum(_albumData) {
        var hasAlbumsNum = wrap.find('.ingallery-app-album').length;
        var aName = (_albumData ? _albumData.name : window.inGallery.getParams().lang.new_album + ' #' + (hasAlbumsNum + 1))
        var albumObj = $(albumTmpl.replace('{title}', aName).replace(/\{id\}/g, hasAlbumsNum));
        albumObj.find('.ingallery-app-album-name').val(aName);
        wrap.find('.ingallery-app-albums-wrap').append(albumObj);
        albumObj.find('.ingallery-app-album-name').on('keydown keyup change', function () {
            albumObj.find('.ingallery-app-album-title span').html(this.value);
        });
        if (_albumData) {
            albumObj.find('.ingallery-app-album-sources').val(_albumData.sources);
            albumObj.find('.ingallery-app-album-limit_items').val(_albumData.limit_items);
            albumObj.find('.ingallery-app-album-cache_lifetime').val(_albumData.cache_lifetime);
            if (_albumData.filters.only) {
                albumObj.find('.ingallery-app-album-filters_only').val(_albumData.filters.only);
            }
            if (_albumData.filters.except) {
                albumObj.find('.ingallery-app-album-filters_except').val(_albumData.filters.except);
            }
        }
        var aSources = '';
        var wShowed = false;
        albumObj.find('.ingallery-app-album-sources').on('change', function(e){
            var prevSourcesAmount = aSources.split(',').length;
            aSources = $(this).val();
            var curSourcesAmount = aSources.split(',').length;
            if(!wShowed && prevSourcesAmount<curSourcesAmount && curSourcesAmount>3){
                wShowed = true;
                showMessage(getWarningHTML(window.inGallery.getParams().lang.sources_warning, window.inGallery.getParams().lang.warning));
            }
        });
        albumObj.find('input,select').on('change', function (e) {
            controlChanged(e);
            updatePreview();
        });
        createInputs();
        boxSlideIn(albumObj, checkAlbumsDels);
    }

    function removeAlbum(_album) {
        boxSlideOut(_album, checkAlbumsDels);
    }

    function checkAlbumsDels() {
        var delBtns = wrap.find('a.ingallery-app-album-del');
        if (delBtns.length > 1) {
            delBtns.stop().fadeIn(100);
        } else {
            delBtns.stop().fadeOut(100);
        }
    }

    function boxSlideIn(_box, _callback) {
        var _del = _box.find('a.ing-del-btn').hide();
        _box.hide();
        _box.stop().slideDown(200, 'swing', function () {
            _del.fadeIn(100);
            if (_callback) {
                _callback();
            }
        });
    }

    function boxSlideOut(_box, _callback) {
        _box.find('a.ing-del-btn').hide();
        _box.stop().slideUp(200, 'swing', function () {
            _box.remove();
            if (_callback) {
                _callback();
            }
            updatePreview();
        });
    }

    function controlChanged(e) {
        $('[data-depends]').each(function () {
            var self = $(this);
            var depends = self.attr('data-depends').split(';');
            for (var i = 0; i < depends.length; i++) {
                var di = depends[i].split('=');
                var options = di[1].split('|');
                if (options.indexOf(getFormValue(di[0])) < 0) {
                    self.slideUp();
                    return;
                }
            }
            self.slideDown();
        });
    }

    function getFormValue(_name, _default = null) {
        var data = $('.ingAjaxForm').serializeArray();
        for (var i = 0; i < data.length; i++) {
            if (data[i].name == 'gallery[cfg][' + _name + ']') {
                return data[i].value;
            }
        }
        return _default;
    }

    function createInputs() {
        wrap.find('.ing-form-tokenfield').tokenfield({
            createTokensOnBlur: true,
        });
        wrap.find('.ing-form-stepper').ingStepper();
        wrap.find('.ing-form-colorpicker').minicolors({
            control: 'hue',
            defaultValue: '',
            format: 'rgb',
            keywords: '',
            inline: false,
            letterCase: 'lowercase',
            opacity: '0.5',
            position: 'bottom left',
            swatches: [],
            change: function (hex, opacity) {
                var log;
                try {
                    log = hex ? hex : 'transparent';
                    if (opacity)
                        log += ', ' + opacity;
                    console.log(log);
                } catch (e) {
                }
            },
            theme: 'default'
        });
    }

    function getResponsiveItemControl(width, cols, rows, gutter) {
        var html = '<div class="ingallery-responsive-item">';
        html += '<div class="ing-form-row"><div class="ing-form-col"><label>' + window.inGallery.getParams().lang.screen_width + ' (px)</label></div><div class="ing-form-col"><input type="text" name="gallery[cfg][layout_responsive][width][]" value="' + width + '" class="ing-form-control" /></div></div>';
        html += '<div class="ing-form-row"><div class="ing-form-col"><label>' + window.inGallery.getParams().lang.cols + '</label></div><div class="ing-form-col"><input type="number" name="gallery[cfg][layout_responsive][cols][]" class="ing-form-stepper" value="' + cols + '" min="1" /></div></div>';
        //html+= '<div class="ing-form-row" data-depends="layout_type=carousel"><div class="ing-form-col"><label>'+window.inGallery.getParams().lang.rows+'</label></div><div class="ing-form-col"><input type="number" name="gallery[cfg][layout_responsive][rows][]" class="ing-form-stepper" value="'+rows+'" min="1" /></div></div>';
        html += '<div class="ing-form-row"><div class="ing-form-col"><label style="padding:0;">' + window.inGallery.getParams().lang.gutter + ' (px)</label></div><div class="ing-form-col"><input type="number" name="gallery[cfg][layout_responsive][gutter][]" class="ing-form-stepper" value="' + gutter + '" min="0" /></div></div>';
        html += '<a href="#" class="ingallery-app-responsive-del"><i class="ing-icon-cancel-1"></i></a>';
        html += '</div>';
        return $(html);
    }

    function switchPage(pageID) {
        if (currentPageID && currentPageID != pageID) {
            wrap.find('.ingallery-app-menu a').removeClass('active');
        }
        currentPageID = pageID;
        currentScreen = wrap.find('.ingallery-app-screen' + pageID);
        wrap.find('.ingallery-app-menu a[data-href="' + pageID + '"]').addClass('active');
        var index = wrap.find('.ingallery-app-screen').index(currentScreen);
        wrap.find('.ingallery-app-screens-wrap').css('margin-left', '-' + (index * wrap.width()) + 'px');
    }

    function updatePreview() {
        if (updatePreviewTO) {
            window.clearTimeout(updatePreviewTO);
        }
        updatePreviewTO = window.setTimeout(loadData, 500);
    }

    function loadData() {
        window.clearTimeout(updatePreviewTO);
        updatePreviewTO = 0;
        var _container = $('#ingalleryDemoWrap');
        var _form = $('.ingAjaxForm');
        var _actionField = _form.find('#ingAction');
        var _action = _actionField.val();
        _container.html(loaderHTML);
        _actionField.val('gallery.preview');
        _form.ajaxSubmit({
            url: window.inGallery.getParams().ajax_url,
            type: 'post',
            dataType: 'json',
            success: function (response, statusText, xhr, $form) {
                if (!response) {
                    _container.html(getErrorHTML(window.inGallery.getParams().lang.system_error));
                }
                if (response.status && response.status == 'success') {
                    _container.html(response.html);
                    window.inGallery.reInit();
                    $(window).trigger('resize');
                } else if (response.status && response.status == 'error') {
                    _container.html(getErrorHTML(response.message));
                } else {
                    _container.html(getErrorHTML(window.inGallery.getParams().lang.system_error));
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                _container.html(getErrorHTML(textStatus + ' (' + errorThrown + ')'));
            },
            fail: function (jqXHR, textStatus, errorThrown) {
                _container.html(getErrorHTML(textStatus + ' (' + errorThrown + ')'));
            }
        });
        _actionField.val(_action);
    }

    function getWarningHTML(msg, title=null) {
        var html = '<div class="ing-warning">';
        if(title){
            html += '<div class="ing-warning-title">' + title + '</div>';
        }
        html += '<div class="ing-warning-message">' + msg + '</div>';
        html += '</div>';
        return html;
    }
    
    function getErrorHTML(msg) {
        var html = '<div class="ing-error">';
        html += '<div class="ing-error-title">' + window.inGallery.getParams().lang.error_title + '</div>';
        html += '<div class="ing-error-message">' + msg + '</div>';
        html += '</div>';
        return html;
    }
    
    var msgTimeout=0;
    
    function showMessage(msg,autohide){
            hideMessage(true);
            var shade = $('<div id="ing-shade"></div>').appendTo('body');
            shade.on('click',function(){
                    hideMessage();
            });
            shade.hide().fadeIn(200);
            var box = $('<div id="ing-shade-box"></div>').appendTo('body');
            box.append(msg);
            box.css('opacity',0).css('margin-top','-50px');
            box.animate({
                    'opacity': 1,
                    'margin-top': 0
            },200,'swing');
            if(autohide){
                    msgTimeout = window.setTimeout(function(){
                            hideMessage();	
                    },3000);
            }
    }

    function hideMessage(force){
            if(msgTimeout){
                    window.clearTimeout(msgTimeout);
                    msgTimeout = 0;
            }
            var shade = $('#ing-shade');
            var box = $('#ing-shade-box');
            if(shade.length==0 || box.length==0){
                    return false;
            }
            if(force){
                    shade.remove();
                    box.remove();
                    return;
            }
            shade.fadeOut(200,'swing',function(){
                    shade.remove();
            });
            box.animate({
                    'opacity': 0,
                    'margin-top': 50
            },200,'swing',function(){
                    box.remove();
            });
    }


    $(document).ready(function (e) {
        init();
    });


})(jQuery);
(function($){
	'use strict';
	
	var msgTimeout=0;
	
	function showLoading(){
		hideLoading(true);
		$('<div id="ing-shade-loading"><i class="ing-icon-spin2 animate-spin"></i></div>').appendTo('body').hide().fadeIn(200);
	}
	
	function hideLoading(force){
		var box = $('#ing-shade-loading');
		if(box.length==0){
			return;
		}
		if(force){
			box.remove();
			return;
		}
		box.fadeOut(200,'swing',function(){
			box.remove();
		});
	}
	
	function showMessage(msg,autohide){
		hideMessage(true);
		var shade = $('<div id="ing-shade"></div>').appendTo('body');
		shade.on('click',function(){
			hideMessage();
		});
		shade.hide().fadeIn(200);
		var box = $('<div id="ing-shade-box"></div>').appendTo('body');
		box.append(msg);
		box.css('opacity',0).css('margin-top','-50px');
		box.animate({
			'opacity': 1,
			'margin-top': 0
		},200,'swing');
		if(autohide){
			msgTimeout = window.setTimeout(function(){
				hideMessage();	
			},3000);
		}
	}
	
	function hideMessage(force){
		if(msgTimeout){
			window.clearTimeout(msgTimeout);
			msgTimeout = 0;
		}
		var shade = $('#ing-shade');
		var box = $('#ing-shade-box');
		if(shade.length==0 || box.length==0){
			return false;
		}
		if(force){
			shade.remove();
			box.remove();
			return;
		}
		shade.fadeOut(200,'swing',function(){
			shade.remove();
		});
		box.animate({
			'opacity': 0,
			'margin-top': 50
		},200,'swing',function(){
			box.remove();
		});
	}
	
	function getErrorHTML(msg){
		var html = '<div class="ing-error">';
		html+= '<div class="ing-error-title">'+window.inGallery.getParams().lang.error_title+'</div>';
		html+= '<div class="ing-error-message">'+msg+'</div>';
		html+= '</div>';
		return html;
	}
	
	function getSuccessHTML(msg){
		var html = '<div class="ing-success">';
		html+= '<div class="ing-success-title">'+window.inGallery.getParams().lang.congrats+'</div>';
		html+= '<div class="ing-success-message">'+msg+'</div>';
		html+= '</div>';
		return html;
	}
    
    function setCookie(name,value,days) {
		var expires = "";
		if (days) {
			var date = new Date();
			date.setTime(date.getTime() + (days*24*60*60*1000));
			expires = "; expires=" + date.toUTCString();
		}
		document.cookie = name + "=" + (value || "")  + expires + "; path=/";
	}
	
	function getCookie(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	}
    
    var hideReviewBoxForever = false;
    
    function hideReviewBox(forever=false){
        if(forever || hideReviewBoxForever){
            setCookie('ingallery_backend_review_deny', 1, 999);
        }
        else{
            setCookie('ingallery_backend_review_deny', 1, 7);
        }
        var shade = $('#ing-shade');
        var box = $('#ing-review-box');
        shade.fadeOut(200,'swing',function(){
            shade.remove();
        });
        box.fadeOut(200,'swing',function(){
            box.remove();
        });
    }
    
    function openRateWindow(){
        var win = window.open("https://extensions.joomla.org/extensions/extension/social-web/social-display/ingallery/", "_blank");
        var box = $('#ing-review-box');
        var boxBody = box.find('#ing-review-box-body');
        var boxFooter = box.find('#ing-review-box-footer');
        boxBody.empty();
        boxBody.append('<div class="ing-review-box-title">'+window.inGallery.getParams().lang.thanks4review+'</div>');
        boxBody.append('<div class="ing-review-box-subtitle">'+window.inGallery.getParams().lang.thanks4review2+'</div>');
        boxBody.append('<div class="ing-review-box-info">'+window.inGallery.getParams().lang.thanks4review3+' Joomla! Extensions Directory</div>');
        
        boxFooter.empty();
        boxFooter.append('<span class="ing-review-box-btn ok">OK</span>');
        hideReviewBoxForever = true;
        return false;
    }
    
    function setRate(starsBox, num){
        starsBox.find('i').each(function(index){
            var _class = (index<num?'ing-icon-heart-2':'ing-icon-heart-empty');
            $(this).attr('class', _class);
        });
    }
    
    function askForReview(){
        return;
        var html = '<div id="ing-review-box">'
                        +'<div id="ing-review-box-body">'
                            +'<div class="ing-review-box-title">'+window.inGallery.getParams().lang.enjoyingall+'</div>'
                            +'<div class="ing-review-box-subtitle">'+window.inGallery.getParams().lang.clicktorate+'</div>'
                            +'<div class="ing-review-box-stars"><i data-num="1" class="ing-icon-heart-empty"></i><i data-num="2" class="ing-icon-heart-empty"></i><i data-num="3" class="ing-icon-heart-empty"></i><i data-num="4" class="ing-icon-heart-empty"></i><i data-num="5" class="ing-icon-heart-empty"></i></div>'
                        +'</div>'
                        +'<div id="ing-review-box-footer">'
                            +'<span class="ing-review-box-btn no">'+window.inGallery.getParams().lang.nothanks+'</span>'
                        +'</div>'
                    +'</div>'
        ;
        var shade = $('<div id="ing-shade"></div>').appendTo('body');
        var box = $(html).appendTo('body');
        var boxBody = box.find('#ing-review-box-body');
        var boxFooter = box.find('#ing-review-box-footer');
        
		shade.hide().fadeIn(200);
        box.hide().fadeIn(200);
        
        shade.on('click',function(){
			hideReviewBox();
		});
        
        $(document).on('click','.ing-review-box-btn.no, .ing-review-box-btn.ok',function(e){
            hideReviewBox();
        });
        $(document).on('click','.ing-review-box-btn.rate',function(e){
            openRateWindow();
            return false;
        });
        
        
        var starsBox = box.find('.ing-review-box-stars');
        starsBox.find('i').on('mouseenter', function(){
            var $this = $(this);
            var num = parseInt($this.attr('data-num'));
            setRate(starsBox, num);
        }).on('click', function(){
            starsBox.find('i').off('mouseenter');
            var $this = $(this);
            var num = parseInt($this.attr('data-num'));
            if(num!=5){
                hideReviewBox(true);
                return false;
            }
            boxBody.append('<div class="ing-review-box-info">'+window.inGallery.getParams().lang.sharereview+' <a href="https://extensions.joomla.org/extensions/extension/social-web/social-display/ingallery/" target="_blank">Joomla! Extensions Directory</a></div>');
            boxFooter.append('<span class="ing-review-box-btn rate">'+window.inGallery.getParams().lang.rateus+'</span>');
            
            boxBody.find('a').on('click',openRateWindow);
        });
    }
	
	$(document).ready(function(e) {

		window.inGallery.addRequestData = function(gallery){
			if(!gallery.parent().is('#ingalleryDemoWrap')){
				return '';
			}
			var form = $('.ingAjaxForm');
			var actionField = form.find('#ingAction');
			var action = actionField.val();
			actionField.val('gallery.preview');
			var data = form.serialize();
			actionField.val(action);
			return data;
		}
        
        /*
        if(window.inGalleryAskRate && getCookie('ingallery_backend_review_deny')===null){
            askForReview();
        }
		*/
    });
	
})(jQuery);