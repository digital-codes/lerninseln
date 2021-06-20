<template>
    <div>
		<calendar-view
			:show-date="showDate"
			class="theme-default"
      :items="items"
      :starting-day-of-week="startingDayOfWeek"
      :time-format-options="{ hour: 'numeric', minute: '2-digit' }"
      :enable-date-selection="true"
      :selection-start="selectionStart"
      :selection-end="selectionEnd"
      :disable-past="disablePast"
      @click-item="onClickItem"
      @date-selection-start="setSelection"
      @date-selection="setSelection"
      @date-selection-finish="finishSelection"
      @drop-on-date="onDrop"
      @click-date="onClickDay"
      >
      <template #header="{ headerProps }">
					<calendar-view-header slot="header" :header-props="headerProps" @input="setShowDate" />
				</template>

		</calendar-view>
    </div>
</template>
<script>
// https://tallent.us/vue-simple-calendar/
// https://github.com/richardtallent/vue-simple-calendar
// https://github.com/richardtallent/vue-simple-calendar-sample

	import { CalendarView, CalendarViewHeader, CalendarMath } from "vue-simple-calendar"
	
	import "vue-simple-calendar/dist/style.css"
	// The next two lines are optional themes
	import "vue-simple-calendar/static/css/default.css"

	export default {
		name: 'Cal',
		components: {
			CalendarView,
			CalendarViewHeader,
		},
		data: function() {
			return { 
        showDate: new Date(), //this.thisMonth(1),
        message: "",
        startingDayOfWeek: 1,  //1: monday
        disablePast: true,
        disableFuture: false,
        displayPeriodUom: "month",
        displayPeriodCount: 1,
        displayWeekNumbers: false,
        showTimes: true,
        selectionStart: null,
        selectionEnd: null,
        useDefaultTheme: true,
        useHolidayTheme: false,
        useTodayIcons: false,
        items: [
            {
              id: "e0",
              startDate: "2021-06-22",
              title: "Single-day item with a long title",
            }, 
        ],
        }
		},
    computed: {
      userLocale() {
        return CalendarMath.getDefaultBrowserLocale
      },
      dayNames() {
        return CalendarMath.getFormattedWeekdayNames(this.userLocale, "long", 0)
      },
    },    
		methods: {
      setShowDate(d) {
        this.message = `Changing calendar view to ${d.toLocaleDateString()}`
        console.log(this.message)
        this.showDate = d
      },
      setSelection(dateRange) {
        this.selectionEnd = dateRange[1]
        this.selectionStart = dateRange[0]
      },
      finishSelection(dateRange) {
          this.setSelection(dateRange)
          this.message = `You selected: ${this.selectionStart.toLocaleDateString()} -${this.selectionEnd.toLocaleDateString()}`
          console.log(this.message)
        },
      onClickItem(e) {
        this.message = `You clicked: ${e.title}`
        console.log(this.message)
      },
		}
	}
</script>

