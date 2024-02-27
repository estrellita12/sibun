import React, { useEffect, useRef, useState } from "react";
import { styled } from "styled-components";
import { Hour } from "../utils/TimeCalculation";
import apis from "../api/apis";
import dayjs from "dayjs";

import CalendarHeader from "../components/Header/CalendarHeader";
import ScheduleWeek from "../components/Schedule/ScheduleWeek";
import ScheduleList from "../components/Schedule/ScheduleList";
// import MonthCalendar from "../components/Calendar/MonthCalendar";
import { LocalizationProvider } from "@mui/x-date-pickers/LocalizationProvider";
import { AdapterDayjs } from "@mui/x-date-pickers/AdapterDayjs";
import { DateCalendar } from "@mui/x-date-pickers/DateCalendar";
import { Btn } from "../styles/styledComponent";
import { useLocation } from "react-router-dom";
import { seletedDayState } from "../recoil/atom";
import { useRecoilState } from "recoil";

const SchedulePage = () => {
  const { state } = useLocation();
  const [value, setValue] = useState(false);
  const [reservation, setReservation] = useState();
  const [day, setDay] = useRecoilState(seletedDayState);
  const [nowTime, setNowTime] = useState(dayjs().format("HH"));
  const [selectedDay, setSeletedDay] = useState();
  const [modalVisible, setModalVisible] = useState(false);
  const scrollViewRef = useRef([]);
  const storeId = localStorage.getItem("storeId");
  const getInfo = localStorage.getItem("storeInfo");
  const storeInfo = JSON.parse(getInfo);
  const openTime = Hour(storeInfo?.open);
  const time = Hour(storeInfo?.close) - Hour(storeInfo?.open);

  useEffect(() => {
    const arr = [];
    Array.from({ length: time }, (value, index) => {
      return arr.push(openTime + index);
    });
    if (scrollViewRef.current) {
      const index = arr.indexOf(Number(nowTime));

      scrollViewRef.current.scrollTo({
        x: 60 * index,
        y: 0,
        animated: true,
      });
    }
  }, [day, nowTime]);

  useEffect(() => {
    apis.getReservationDay(storeId, day).then((res) => {
      if (res.data.result === "000") {
        setReservation(res.data.list);
      } else {
        setReservation(null);
      }
    });
  }, [day]);

  const reservationRoom = reservation?.reduce((acc, current) => {
    const hour = Hour(current?.reservation_time);
    acc[hour] = acc[hour] || [];
    acc[Number(hour)].push({
      reservation_room_idx: current.reservation_room_idx,
      hour: hour,
    });
    return acc;
  }, {});

  const getOpenValue = () => {
    setModalVisible(!modalVisible);
  };

  const onClickTime = (index, k) => {
    setNowTime(index);
    if (scrollViewRef.current) {
      scrollViewRef.current.scrollTo({ x: 60 * k, y: 0, animated: true });
    }
  };

  const datafilter = reservation?.filter(
    (el) => Hour(el?.reservation_time) === Number(nowTime)
  );

  const onChangeDay = (value) => {
    setSeletedDay(value);
  };
  const onClickDay = () => {
    setModalVisible(!modalVisible);
    setValue(true);
    setDay(dayjs(selectedDay).format("YYYY-MM-DD"));
    setNowTime(dayjs(value).format("HH"));
  };

  return (
    <div>
      {modalVisible ? (
        <ModalBg>
          <Modal>
            <LocalizationProvider
              dateAdapter={AdapterDayjs}
              dateFormats={{ monthAndYear: "YYYY년MM월" }}
            >
              <DateCalendar
                views={["day"]}
                onChange={(value) => onChangeDay(value)}
              />
            </LocalizationProvider>
            <BtnWrap>
              <Btn
                radius={5}
                bg={"#d7d7d7"}
                height={45}
                size={16}
                onClick={() => setModalVisible(false)}
              >
                취소
              </Btn>
              <Btn
                bg={"#f33562"}
                radius={5}
                height={45}
                size={16}
                onClick={() => onClickDay()}
              >
                적용
              </Btn>
            </BtnWrap>
          </Modal>
        </ModalBg>
      ) : (
        ""
      )}
      <CalendarHeader
        getOpenValue={getOpenValue}
        day={dayjs(day).format("MM")}
      />
      <ScheduleWeek
        day={day}
        setDay={setDay}
        value={value}
        setValue={setValue}
        setNowTime={setNowTime}
        selectedDay={state}
      />

      <TimeLine>
        <div className="time" ref={scrollViewRef}>
          {Array.from({ length: time }, (value, index) => {
            const count =
              reservationRoom && reservationRoom[openTime + index]?.length;
            const checkColor = count === undefined ? "#d7d7d7" : "#444";
            const nowTimeValue = Number(nowTime) === Number(openTime + index);
            return (
              <Time
                // border={nowTimeValue ? 2 : 0}
                color={nowTimeValue ? "#f33562" : checkColor}
                key={index}
                onClick={() => onClickTime(openTime + index, index)}
                id={index}
              >
                <Line border={nowTimeValue ? 2 : 0} />
                {openTime + index}시
              </Time>
            );
          })}
        </div>
      </TimeLine>

      <div className="scroll">
        {datafilter && (
          <ScheduleList
            reservation={datafilter}
            storeId={storeId}
            selectedDay={selectedDay}
          />
        )}
      </div>
    </div>
  );
};

const TimeLine = styled.div`
  border-top: 1px solid #e3e3e3;
  border-bottom: 1px solid #e3e3e3;

  position: relative;
  width: 100%;
  height: 50px;
  overflow: auto;

  & .time {
    position: absolute;
    width: 100%;
    height: 100%;
    display: grid;
    grid-auto-columns: 75px;
    grid-auto-flow: column;

    justify-items: center;
  }
`;

const Time = styled.button`
  border-top: ${(props) => props.border}px solid #f33562;
  width: 40px;
  height: 50px;
  font-weight: 500;
  color: ${(props) => props.color};

  display: flex;
  align-items: center;
  justify-content: center;
`;

const Modal = styled.div`
  position: absolute;
  background-color: #fff;
  top: 60px;
  right: 10px;
  border-radius: 10px;
  padding-bottom: 20px;

  & .css-1q04gal-MuiDateCalendar-root {
    width: 310px;
    height: 100%;
  }

  & .css-1u23akw-MuiButtonBase-root-MuiPickersDay-root {
    font-size: 15px;
  }
  & .css-rhmlg1-MuiTypography-root-MuiDayCalendar-weekDayLabel {
    color: #bdbdbd;
  }
  & .css-1u23akw-MuiButtonBase-root-MuiPickersDay-root.Mui-selected {
    background-color: #f33562 !important;
    color: #fff;
    font-size: 15px !important;
  }
  & .css-jgls56-MuiButtonBase-root-MuiPickersDay-root:not(.Mui-selected) {
    font-size: 15px;
  }
`;

const ModalBg = styled.div`
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background: rgba(0, 0, 0, 0.3);
  z-index: 100;
`;

const BtnWrap = styled.div`
  display: grid;
  grid-template-columns: 45% 45%;
  justify-content: space-evenly;
`;

const Line = styled.div`
  position: absolute;
  border: ${(props) => props.border}px solid #f33562;
  width: 40px;
  top: -2px;
  z-index: 200;
`;

export default SchedulePage;
