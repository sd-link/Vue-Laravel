import Picker from 'element-ui/packages/date-picker/src/picker';
import DatePanel from './panel/date';
import DateRangePanel from 'element-ui/packages/date-picker/src/panel/date-range';

const getPanel = function(type) {
  if (type === 'daterange' || type === 'datetimerange') {
    return DateRangePanel;
  }
  return DatePanel;
};

export default {
  mixins: [Picker],

  name: 'ElDatePicker',

  props: {
    type: {
      type: String,
      default: 'date'
    }
  },

  watch: {
    type(type) {
      if (this.picker) {
        this.unmountPicker();
        this.panel = getPanel(type);
        this.mountPicker();
      } else {
        this.panel = getPanel(type);
      }
    }
  },

  created() {
    this.panel = getPanel(this.type);
  },

  methods: {
    handleClose() {
      if (this.picker && this.picker.confirm) {
        this.picker.confirm();
      }
      this.$nextTick(() => {
        this.pickerVisible = false;
      });
    }
  }
};
