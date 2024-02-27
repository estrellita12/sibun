import React from "react";
import { DemoContainer } from "@mui/x-date-pickers/internals/demo";
import { AdapterDayjs } from "@mui/x-date-pickers/AdapterDayjs";
import { LocalizationProvider } from "@mui/x-date-pickers/LocalizationProvider";
import { DatePicker } from "@mui/x-date-pickers/DatePicker";
import dayjs from "dayjs";
import "dayjs/locale/ko";
import styled from "styled-components";

export default function MonthCalendar({ setDate, date }) {
  // const [date, setDate] = useState();

  const onChangeSelectedDay = (value) => {
    const dayjsformat = dayjs(value).format("YYYY-MM-DD");
    setDate(dayjsformat);
  };

  return (
    <Wrap>
      <LocalizationProvider dateAdapter={AdapterDayjs} adapterLocale="ko">
        <DatePicker
          label={date || "날짜선택"}
          format="YYYY-MM-DD"
          showDaysOutsideCurrentMonth
          onChange={(value) => onChangeSelectedDay(value)}
        />
      </LocalizationProvider>
    </Wrap>
  );
}

const Wrap = styled.div``;
