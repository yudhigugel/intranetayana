(function($, undefined) {

  $.fn.monthpicker = function(options) {
      var selected_month = 0,
      year = 0,
      months = [];
      if(typeof(options) == 'object'){
        selected_month = options.hasOwnProperty('months') ? options.months : 0;
        year = options.hasOwnProperty('years') ? options.years.indexOf(options.year) : 0;
        months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Period 13', 'Period 14', 'Period 15', 'Period 16'];
      }

      Monthpicker = function(el) {
        this._el = $(el);
        this._init();
        this._render();
        this._renderYears();
        this._renderMonths();
        this._bind();
      };

    Monthpicker.prototype = {
      destroy: function() {
        this._el.off('click');
        this._yearsSelect.off('click');
        this._container.off('click');
        $(document).off('click', $.proxy(this._hide, this));
        this._container.remove();
      },

      _init: function() {
        // console.log(selected_month, year, options.year);
        if(typeof(options) == 'object'){
          if(options.hasOwnProperty('years')){
            this._el.html(`${months[selected_month]} ${options.years[year]}`);
            this._el.data('monthpicker', this);
            this._el.css('text-decoration', 'none');
          }
        }
      },

      _bind: function() {
        this._el.on('click', $.proxy(this._show, this));
        $(document).on('click', $.proxy(this._hide, this));
        this._yearsSelect.on('click', function(e) { e.stopPropagation(); });
        this._container.on('click', 'button', $.proxy(this._selectMonth, this));
      },

      _show: function(e) {
        e.preventDefault();
        e.stopPropagation();
        this._container.css('display', 'inline-block');
      },

      _hide: function() {
        this._container.css('display', 'none');
      },

      _selectMonth: function(e) {
        if (options.onMonthSelect) {
          var monthIndex = $(e.target).data('value'),
            month = months[monthIndex],
            year = this._yearsSelect.val();
            // if(options.hasOwnProperty('currently_sync') && !options.currently_sync){
              this._el.html(month + ' ' + year);
            // }
            options.onMonthSelect(monthIndex, year);
        }
      },

      _render: function() {
        var linkPosition = this._el.position(),
          cssOptions = {
            display:  'none',
            position: 'absolute',
            top:      linkPosition.top + this._el.height() + (options.topOffset || 0),
            left:     linkPosition.left + (options.leftOffset || 0),
            "max-width": '350px'
          };
        // console.log(this._el.height(), options.topOffset, linkPosition.top, (new Date).valueOf());
        this._id = (new Date).valueOf();
        this._container = $('<div class="monthpicker" id="monthpicker-' + this._id +'">')
          .css(cssOptions)
          .appendTo($('body'));
      },

      _renderYears: function() {
        var markup = $.map(options.years, function(year) {
          var set_selected = (year == options.year) || 0 ? "selected" : '';
          return `<option ${set_selected}>` + year + '</option>';
        });
        var yearsWrap     = $('<div class="years">').appendTo(this._container);
        this._yearsSelect = $('<select>').html(markup.join('')).appendTo(yearsWrap);
      },

      _renderMonths: function() {
        var markup = ['<table>', '<tr>'];
        $.each(months, function(i, month) {
          if (i > 0 && i % 4 === 0) {
            markup.push('</tr>');
            markup.push('<tr>');
          }
          markup.push('<td><button data-value="' + i + '">' + month +'</button></td>');
        });
        markup.push('</tr>');
        markup.push('</table>');
        this._container.append(markup.join(''));
      }
    };

    var methods = {
      destroy: function() {
        var monthpicker = this.data('monthpicker');
        if (monthpicker) monthpicker.destroy();
        return this;
      }
    }

    if ( methods[options] ) {
        return methods[ options ].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof options === 'object' || ! options ) {
      return this.each(function() {
        return new Monthpicker(this);
      });
    } else {
      $.error( 'Method ' +  options + ' does not exist on monthpicker' );
    }

  };

}(jQuery));
