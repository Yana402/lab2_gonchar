function validateDate(e){const a=e.querySelector('input[name="date_time"]'),t=new Date(a.value),u=new Date;u.setHours(0,0,0,0);const l=(new Date).getFullYear(),n=`${l}-01-01T00:00`,r=`${l+5}-12-31T23:59`;return!(""===a.value||"0000-00-00T00:00"===a.value||"9999-12-31T00:00"===a.value||a.value<n||a.value>r||t<u)||(alert("Пожалуйста, введите корректную дату и время. Дата должна быть в диапазоне с "+n+" по "+r+", и не может быть в прошлом."),!1)}