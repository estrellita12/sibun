import apis from "../api/apis";

export function ReservationStt(stt) {
  switch (Number(stt)) {
    case 1:
      return "대기";
    case 2:
      return "확정";
    case 3:
      return "취소";
    case 4:
      return "노쇼";
    case 5:
      return "입장";
  }
}

export async function ChangeStt(idx, stt) {
  console.log(idx, stt);
  let changeResult = "";
  await apis.getReservationStt(idx, stt).then((res) => {
    const result = res.data.result;
    if (result === "000") {
      const title =
        stt === "cancel" ? "예약을 거절하였습니다." : "예약을 확정하였습니다.";
      alert(`${title}`);
      changeResult = "000";
    }
  });

  return changeResult;
}
