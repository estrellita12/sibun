function dayValue(day) {
  switch (Number(day)) {
    case 1:
      return "월";
    case 2:
      return "화";
    case 3:
      return "수";
    case 4:
      return "목";
    case 5:
      return "금";
    case 6:
      return "토";
    case 7:
      return "일";
  }
}

export function MakeWeekFun(data) {
  const days = data?.split(",");
  const newArr = days.map(Number);
  const sort = newArr.sort();
  const weekdays = [1, 2, 3, 4, 5];
  const weekend = [6, 7];

  const arr = [];
  sort?.map((day, k) => {
    arr.push(`${dayValue(day)}${k + 1 === days.length ? "" : " / "}`);
  });

  if (sort.toString() === weekdays.toString()) {
    return "평일";
  } else if (sort.toString() === weekend.toString()) {
    return "주말";
  } else {
    return arr;
  }
}
