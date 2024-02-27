import React from "react";

import { Display, Title } from "../../styles/styledComponent";
import { styled } from "styled-components";

const WeekDay = ({ day, setDay }) => {
  const days = [
    { title: "월", value: "1" },
    { title: "화", value: "2" },
    { title: "수", value: "3" },
    { title: "목", value: "4" },
    { title: "금", value: "5" },
    { title: "토", value: "6" },
    { title: "일", value: "7" },
  ];

  const onClickDay = (e) => {
    if (day?.includes(e)) {
      console.log(day);
      const filter = day?.filter((el) => el !== e);
      setDay(filter);
    } else {
      setDay([...day, e]);
    }
  };

  return (
    <WeekWrap>
      {days.map((i, k) => {
        const dayCheck = day?.includes(i?.value);
        return (
          <Btn
            bg={dayCheck ? "rgba(243, 53, 98, 0.05)" : "#fff"}
            color={dayCheck ? "#F33562" : "#d7d7d7"}
            key={k}
            onClick={() => onClickDay(i?.value)}
          >
            <Title
              color={dayCheck ? "#F33562" : "#d7d7d7"}
              size={14}
              weight={400}
            >
              {i.title}
            </Title>
          </Btn>
        );
      })}
    </WeekWrap>
  );
};

const WeekWrap = styled(Display)`
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  justify-items: center;
  align-items: center;
  grid-gap: 10px;

  margin-top: 10px;
`;

const Btn = styled.button`
  border: 1px solid;
  border-radius: 5px;
  width: 100%;
  height: 55px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-color: ${(props) => props.color};
  background-color: ${(props) => props.bg};
`;

export default WeekDay;
