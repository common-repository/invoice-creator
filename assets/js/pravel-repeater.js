jQuery(document).ready(function($){
	jQuery.fn.extend({
		createRepeater: function (options = {}) {
			var hasOption = function (optionKey) {
				return options.hasOwnProperty(optionKey);
			};
			var option = function (optionKey) {
				return options[optionKey];
			};
			var addItem = function (items, key, fresh = true) {
				var itemContent = items;
				var group = itemContent.data("group");
				var item = itemContent;
				var input = item.find('input,select');
				input.each(function (index, el) {
					var attrName = $(el).data('name');
					var skipName = $(el).data('skip-name');
					if(attrName == 'product_select_from_list')
					{
						$(el).attr("onchange", 'selected_product_price(this, ' + key + ')');
						$(el).attr('id', 'selected_product_price_'+key);
					}
					if(attrName == 'qty_by_customer')
					{
						$(el).attr("oninput", 'selected_product_qty_by_customer(this, ' + key + ')');
						$(el).attr('id', 'selected_product_qty_by_customer'+key);
					}
					if(attrName == 'product_total_price')					{
						
						$(el).attr('id', 'product_total_price'+key);
					}
					if(attrName == 'product_total_price_new')					{
						
						$(el).attr('id', 'product_total_price_new'+key);
					}
					if(attrName == 'product_price')
					{
						$(el).attr("oninput", 'product_price(this, ' + key + ')');
						$(el).attr('id', 'product_price'+key);
					}
				
					if(attrName == 'qty_by_customer_new')
					{
						$(el).attr("oninput", 'qty_by_customer_new(this, ' + key + ')');
						$(el).attr('id', 'qty_by_customer_new'+key);
					}
					if (skipName != true) {
						$(el).attr("name", attrName + "[" + key + "]");
					} else {
						if (attrName != 'undefined') {
							$(el).attr("name", attrName);
						}
					}
					if (fresh == true) {
						//$(el).attr('value', '');
					}
				})
				var itemClone = items;

				/* Handling remove btn */
				var removeButton = itemClone.find('.remove-btn');
				 
				if (key == 0) {
					removeButton.attr('disabled', true);
				} else {
					removeButton.attr('disabled', false);
				}

				//removeButton.attr('onclick', 'jQuery(this).parents(\'.items\').remove()');
				removeButton.attr('onclick', 'remove_item(this, '+key+')');
				if(key != 0){
					var key_before = key - 1;
					for(var i = 0; i<=key_before; i++){
						removeButton.removeClass('remove_item_'+i);
					}					
				}
				removeButton.addClass('remove_item_'+key);
				removeButton.attr('row_total', 0);

				$("<div class='items'>" + itemClone.html() + "<div/>").appendTo(repeater);
			};
			/* find elements */
			var repeater = this;
			var items = repeater.find(".items");
			var key = 0;
			var addButton = repeater.find('.repeater-add-btn');

			items.each(function (index, item) {
				items.remove();
				if (hasOption('showFirstItemToDefault') && option('showFirstItemToDefault') == true) {
					addItem($(item), key);
					key++;
				} else {
					if (items.length > 1) {
						addItem($(item), key);
						key++;
					}
				}
			});

			addButton.on("click", function () {
				addItem($(items[0]), key);
				key++;
			});
		}
	});
});