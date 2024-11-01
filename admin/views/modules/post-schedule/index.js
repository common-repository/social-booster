import Vue from 'vue';
import datePicker from 'vue-bootstrap-datetimepicker';
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
jQuery.extend(true, jQuery.fn.datetimepicker, {
  icons: {
    time: 'far fa-clock',
    date: 'far fa-calendar',
    up: 'fas fa-arrow-up',
    down: 'fas fa-arrow-down',
    previous: 'fas fa-chevron-left',
    next: 'fas fa-chevron-right',
    today: 'fas fa-calendar-check',
    clear: 'far fa-trash-alt',
    close: 'far fa-times-circle'
  }
});
Vue.component('PostSchedule', {
    template: '#rx-sb-module-post-schedule',
    data() {
        return {
            scheduleSwitcher:'',
            checked:'',
            reposterSelected: 'weekly',
            reposters: [
              { value: 'weekly', text: 'Weekly' },
              { value: 'monthly', text: 'Monthly' },
            ],
            durationSelected: 'oneDay',
            durations: [
              { value: 'oneDay', text: '1 Day' },
              { value: 'twoDay', text: '2 Day' },
            ],
            date: new Date(),
            time: new Date(),
            dateOptions: {
                format: 'DD/MM/YYYY',
            },
            timeOptions: {
                format: 'LT',
            }
        };
    }
});
