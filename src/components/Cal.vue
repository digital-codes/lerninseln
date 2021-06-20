<template>
    <div>
		<calendar-view
			:show-date="showDate"
			class="theme-default">
			<template #header="{ headerProps }">
				<calendar-view-header
					:header-props="headerProps"
					@input="setShowDate" />
			</template>
		</calendar-view>
    </div>
</template>
<script>
// https://github.com/richardtallent/vue-simple-calendar

	import { CalendarView, CalendarViewHeader } from "vue-simple-calendar"
	
	import "vue-simple-calendar/dist/style.css"
	// The next two lines are optional themes
	import "vue-simple-calendar/static/css/default.css"

	export default {
		name: 'Cal',
		data: function() {
			return { showDate: new Date() }
		},
		components: {
			CalendarView,
			CalendarViewHeader,
		},
		methods: {
			setShowDate(d) {
				this.showDate = d;
			},
		}
	}
</script>

