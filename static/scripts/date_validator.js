function validateDate(form) {
  const dateInput = form.querySelector('input[name="date_time"]');
  const dateValue = new Date(dateInput.value);
  const currentDate = new Date();

  // Установим время текущей даты в 00:00:00 для сравнения
  currentDate.setHours(0, 0, 0, 0);

  const currentYear = new Date().getFullYear();
  const minDate = `${currentYear}-01-01T00:00`; // Минимальная дата (начало текущего года)
  const maxDate = `${currentYear + 5}-12-31T23:59`; // Максимальная дата (5 лет в будущем)

  // Проверка на правильность даты
  if (
    dateInput.value === "" ||
    dateInput.value === "0000-00-00T00:00" ||
    dateInput.value === "9999-12-31T00:00" ||
    dateInput.value < minDate ||
    dateInput.value > maxDate ||
    dateValue < currentDate
  ) {
    alert(
      "Пожалуйста, введите корректную дату и время. Дата должна быть в диапазоне с " +
        minDate +
        " по " +
        maxDate +
        ", и не может быть в прошлом."
    );
    return false; // Отменяем отправку формы
  }
  return true; // Разрешаем отправку формы
}
