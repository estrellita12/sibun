import React, { useEffect, useState } from "react";
import dayjs from "dayjs";

import { styled } from "styled-components";
import { Title } from "../../styles/styledComponent";
import { seletedDayState } from "../../recoil/atom";
import { useRecoilState } from "recoil";

const ScheduleWeek = ({
  day,
  setDay,
  value,
  setValue,
  setNowTime,
  selectedDay,
}) => {
  const [currentDay, setCurrentDay] = useRecoilState(seletedDayState);
  const [dayValue, setDayValue] = useState(0);

  useEffect(() => {
    if (value) {
      setDayValue(0);
      setCurrentDay(day);
    }
    setValue(false);
  }, [value === true]);

  function DayofWeek(day) {
    switch (Number(day)) {
      case 1:
        return "M";
      case 2:
        return "T";
      case 3:
        return "W";
      case 4:
        return "T";
      case 5:
        return "F";
      case 0:
      case 6:
        return "S";
    }
  }

  const onClickDate = (index, date) => {
    const dateformat = dayjs(date).format("YYYY-MM-DD");
    setDayValue(index);
    setDay(dateformat);
    setNowTime(dayjs().format("HH"));
  };

  return (
    <Container>
      <Display>
        {Array.from({ length: 7 }, (value, index) => {
          const colorValue = index === dayValue;
          const date = dayjs(currentDay).add(index, "day");
          return (
            <Week
              key={index}
              bg={colorValue ? "#444" : "#fff"}
              onClick={() => onClickDate(index, date)}
            >
              <Title
                key={index}
                color={colorValue ? "#ffff" : "#BDBDBD"}
                size={13}
                weight={600}
              >
                {DayofWeek(dayjs(date).format("d"))}
              </Title>
              <Title color={colorValue ? "#ffff" : "#515151"} weight={600}>
                {dayjs(date).format("D")}
              </Title>
            </Week>
          );
        })}
      </Display>
    </Container>
  );
};

const Container = styled.div`
  padding: 5px 10px;
  background-color: #fff;
`;

const Display = styled.div`
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  height: 65px;
`;

const Week = styled.button`
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-around;
  width: 40px;
  background-color: ${(props) => props.bg};
  border-radius: 9px;
`;

export default ScheduleWeek;
