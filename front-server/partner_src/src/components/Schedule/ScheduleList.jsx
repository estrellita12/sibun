import React, { useEffect, useState } from "react";

import { styled } from "styled-components";
import dayjs from "dayjs";
import { Title } from "../../styles/styledComponent";
import {
  Hour,
  Minute,
  Period,
  TimeSetting,
  Time,
} from "../../utils/TimeCalculation";
import { useNavigate } from "react-router-dom";

const ScheduleList = ({ reservation, storeId, selectedDay }) => {
  const navigation = useNavigate();
  function sttValue(index) {
    switch (Number(index)) {
      case 1:
        return {
          color: "#FFA0B1",
          title: "대기",
        };
      case 2:
        return {
          color: "#8AE1A2",
          title: "확정",
        };
      case 3:
        return {
          color: "#D9D9D9",
          title: "취소",
        };
      case 4:
        return {
          color: "#F3CE97",
          title: "노쇼",
        };
      case 5:
        return {
          color: "#72D3E0",
          title: "입장",
        };
    }
  }

  return (
    <div>
      {reservation === undefined ? (
        <span>예약된 정보가 없습니다!</span>
      ) : (
        <div>
          {reservation &&
            reservation?.map((i, index) => {
              const hourvalue = Hour(i?.reservation_time);
              const minutevalue = Minute(i?.reservation_time);
              const endTime = TimeSetting(
                hourvalue,
                minutevalue,
                Period(i.reservation_period)
              );
              const stt = i.reservation_stt;
              return (
                <List key={index}>
                  <Room>
                    <Title size={18} weight={600}>
                      {i?.reservation_room_idx}번
                    </Title>
                  </Room>
                  <TimeView>
                    <Title size={16} weight={400}>
                      {Time(i?.reservation_time)} ~ {endTime} (
                      {Period(i.reservation_period)}
                      분)
                    </Title>
                  </TimeView>

                  <SttBg className="center">
                    <Stt color="#fff" bg={sttValue(stt).color} className="font">
                      {sttValue(stt).title}
                    </Stt>
                  </SttBg>
                  <button
                    className="center font"
                    onClick={() =>
                      navigation(`/reservationdetail/${i.reservation_idx}`, {
                        state: {
                          id: storeId,
                          day: dayjs(selectedDay).format("YYYY-MM-DD"),
                          page: "/schedule",
                        },
                      })
                    }
                  >
                    상세
                  </button>
                </List>
              );
            })}
        </div>
      )}
    </div>
  );
};

const Color = styled.div`
  border-color: #efefef;
`;

const List = styled.div`
  display: grid;

  align-items: center;
  grid-template-columns: 20% 50% 15% 15%;
  border-bottom: 1px solid #efefef;

  height: 55px;

  & .center {
    display: flex;
    justify-content: center;
  }

  & .font {
    font-size: 15px;
  }
`;
const TimeView = styled.div`
  padding: 0px 15px;
`;
const Room = styled.div`
  border-right: 1px solid #efefef;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
`;

const SttBg = styled.div`
  /* background-color: ${(props) => props.bg}; */
  border-radius: 20px;
  padding: 5px;
  width: 100%;
`;

const Stt = styled(Title)`
  background-color: ${(props) => props.bg};
  padding: 3px 8px;
  border-radius: 50px;
`;

export default ScheduleList;
