function validateFile(event, name) {
  event.preventDefault();

  const data = new FormData(event.target);
  const image = data.get(name);

  if (image.name != "" && image.size == 0) {
    event.target.lastElementChild.previousElementSibling.innerText =
      "Ошибка: файл отсутствует или недоступен";
  } else {
    event.target.submit();
  }
}
