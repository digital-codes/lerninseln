
<template>
  <FullCalendar defaultView="dayGridMonth" :plugins="calendarPlugins" />
</template>

<script>

import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction';

export default {
    name: "Cal",
  components: {
    FullCalendar // make the <FullCalendar> tag available
  },
  data() {
    return {
      calendarPlugins: [ dayGridPlugin, interactionPlugin ]
    }
  }
}
</script>
