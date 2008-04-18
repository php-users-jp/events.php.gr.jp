/**
 * resizable textarea
 *
 * @see http://espion.just-size.jp/archives/06/237175908.html
 */
var ElementResizer = {
   moveNode: false,

   add: function(elm) {
      var obj = new this._ChildElement(this, elm);

      addEvent(elm, 'mousedown', function (e) {
         if(obj.resizePoint(e)) {
            ElementResizer.moveNode = obj;
         }
      });

      return obj;
   },

   start: function() {
      var self = this;
      addEvent(document.body, 'mouseup', function (e) {
         if(self.moveNode) self.moveNode = false;
      });

      addEvent(document.body, 'mousemove', function (e) {
         if(self.moveNode) self.moveNode.doResize(e);
      });
   },

   resizePoint: function(e, elm) {
      var offset = Position.offset(elm);
      var page   = Position.page(e);

      offset.y += elm.offsetHeight;
      offset.x += elm.offsetWidth;

      if(offset.y - 32 < page.y && page.y < offset.y &&
         offset.x - 32 < page.x && page.x < offset.x - 8) {
         return true;
      }
   },

   doResize: function(e, elm) {
      var offset = Position.offset(elm);
      var page   = Position.page(e);

      var width  = page.x - offset.x + 24;
      var height = page.y - offset.y + 16;
      if (width  < 50) width  = 50;
      if (height < 50) height = 50;

      elm.style.height = height + 'px';
      elm.style.width  = width  + 'px';
   }
}

ElementResizer._ChildElement = function() {
   this.initialize.apply(this, arguments);
}

ElementResizer._ChildElement.prototype = {
   initialize: function(base, elm) {
      this.base = base;
      this.elm  = elm;
   },

   resizePoint: function(e) {
      return this.base.resizePoint(e, this.elm);
   },

   doResize: function(e) {
      return this.base.doResize(e, this.elm);
   }
}

var Position = {
   offset: function(elm) {
      var pos = {};
      pos.x = this.getOffset('Left', elm);
      pos.y = this.getOffset('Top', elm);
      return pos;
   },

   getOffset: function(prop, el) {
      if(el.offsetParent.tagName.toLowerCase() == "body")
         return el['offset'+prop];
      else
         return el['offset'+prop]+ this.getOffset(prop, el.offsetParent);
   },

   page: (document.all) ?
      (function() {
         var pos = {};
         pos.x = event.x + document.body.scrollLeft;
         pos.y = event.y + document.body.scrollTop;
         return pos;
      })
      :
      (function(e) {
         var pos = {};
         pos.x = e.pageX;
         pos.y = e.pageY;
         return pos;
      })
}

var addEvent = (window.addEventListener) ?
   (function(elm, type, event) {
      elm.addEventListener(type, event, false);
   }) : (window.attachEvent) ?
   (function(elm, type, event) {
      elm.attachEvent('on'+type, event);
   }) :
   (function(elm, type, event) {
      elm['on'+type] = event;
   }) ;

addEvent(window, 'load', function() {
   var textareas = document.getElementsByTagName('textarea');

   for(var i = 0;elm = textareas[i];i++) {
      (function(elm) {
         var obj = ElementResizer.add(elm);
         var cursor = false;

         addEvent(elm, 'mousemove', function (e) {
            if(!cursor && obj.resizePoint(e)) {
               elm.style.cursor = 'se-resize';
               cursor = true;
            } else if(cursor && !obj.resizePoint(e)) {
               elm.style.cursor = 'default';
               cursor = false;
            }
         });
      })(elm);
   }

   ElementResizer.start();
});
