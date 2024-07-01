import each from 'lodash/each'
import filter from 'lodash/filter'

export default {
  emits: ['field-shown', 'field-hidden'],

  data: () => ({
    visibleFieldsForPanel: {},
  }),

  created() {
    each(this.panel.fields, field => {
      this.visibleFieldsForPanel[field.attribute] = field.visible
    })
  },

  methods: {
    handleFieldShown(field) {
      this.visibleFieldsForPanel[field] = true
      this.$emit('field-shown', field)
    },

    handleFieldHidden(field) {
      this.visibleFieldsForPanel[field] = false
      this.$emit('field-hidden', field)
    },
  },

  computed: {
    visibleFieldsCount() {
      return Object.entries(
        filter(this.visibleFieldsForPanel, visible => visible === true)
      ).length
    },
  },
}
