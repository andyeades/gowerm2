define([
  'uiRegistry',
  'Magento_Ui/js/form/element/select'
], function (
  uiRegistry,
  Select
) {
  'use strict';

  return Select.extend({

    defaults: {
      mapper: []
    },

    initialize: function () {
      this._super();


      //    console.log(this.value());
     // alert(this.value());
    //  return this.setDependentOptions(this.value());
    },

    /**
     * On value change handler.
     *
     * @param {String} value
     */
    onUpdate: function (value) {
      this.setDependentOptions(value);
      return this._super();
    },


    /**
     * Set options to dependent select
     *
     * @param {String} value
     */
    setDependentOptions: function (value) {

      //var index  = this.containers[0].index;
      // console.log(index);
      // console.log(Object.keys(containers)[0]); // "a"

      window.andy = this;
      var string = this.parentName;
      var afterDot = string.substring(string.lastIndexOf(".") + 1);

      //console.log(afterDot);

      var options = this.mapper['map'][value];


      //    var element  = '${ $.parentName }.${ $.index }_input';

      //   console.log(element);
      var registry = require('uiRegistry');
      registry.filter(function (value, key) {
// console.log(key);
//   console.log(value); 
      });


      var originalOptionfield = uiRegistry.get('elevate_landingpages_form.elevate_landingpages_form.attribute_options_select_container.attribute_options_select.' + afterDot + '.original_option_id');
      console.log("Y");
      console.log(originalOptionfield);
      var field = uiRegistry.get('elevate_landingpages_form.elevate_landingpages_form.attribute_options_select_container.attribute_options_select.' + afterDot + '.option_id');
//elevate_landingpages_form.elevate_landingpages_form.attribute_options_select_container.attribute_options_select.1.attribute
//VM15737:2 elevate_landingpages_form.elevate_landingpages_form.attribute_options_select_container.attribute_options_select.1.attribute_option            


      //  var field = uiRegistry.get('index = attribute_option');
      if (field) {
        field.setOptions(options);
      }
      return this;
    }
  });
});