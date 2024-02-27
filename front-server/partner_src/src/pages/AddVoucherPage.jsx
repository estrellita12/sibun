import React, { useState } from "react";
import { useLocation, useNavigate, useParams } from "react-router-dom";
import NavigationHeader from "../components/Header/NavigationHeader";
import styled from "styled-components";
import { Img, Title } from "../styles/styledComponent";
import WeekDay from "../components/Week/WeekDay";
import IconCalendar from "../assets/image/calendar.png";
import MonthCalendar from "../components/Calendar/MonthCalendar";
import dayjs from "dayjs";
import BasicTimePicker from "../components/TimePicker/BasicTimePicker";
import BottomBtn from "../components/BottomBtn/BottomBtn";
import { ChangeTimeData, Time } from "../utils/TimeCalculation";
import apis from "../api/apis";

const AddVoucherPage = () => {
  const navigate = useNavigate();
  const now = dayjs().format("hh:mm");
  const { state } = useLocation();
  const { id } = useParams();
  const storeId = localStorage.getItem("storeId");
  const [day, setDay] = useState(
    !!state ? state?.store_voucher_available_days.split(",") : []
  );
  const [startDay, setStartDay] = useState(
    !!state ? state?.store_voucher_beg_date : dayjs().format("YYYY-MM-DD")
  );
  const [endDay, setEndDay] = useState(
    !!state ? state?.store_voucher_end_date : dayjs().format("YYYY-MM-DD")
  );
  const [startTime, setStartTime] = useState(
    !!state ? `${Time(state?.store_voucher_beg_time)}` : dayjs().format("HH:mm")
  );
  const [endTime, setEndTime] = useState(
    !!state ? `${Time(state?.store_voucher_end_time)}` : dayjs().format("HH:mm")
  );
  const [voucherName, setVoucherName] = useState(
    !!state ? state?.store_voucher_title : ""
  );
  const [voucherDetail, setVoucherDetail] = useState(
    !!state ? state?.store_voucher_desc : ""
  );
  const [voucherRate, setVoucherRate] = useState(
    !!state ? state?.store_voucher_discount_rate : ""
  );
  const [voucherCnt, setVoucherCnt] = useState(
    !!state ? state?.store_voucher_daily_total_cnt : ""
  );

  function postVoucher(storeId, voucherIdx, data) {
    apis.postVoucher(storeId, voucherIdx, data).then((res) => {
      console.log("수정", res);
      if (res.data.result === "000") {
        navigate("/voucher");
      }
    });
  }

  function putVoucher(storeId, data) {
    apis.putVoucher(storeId, data).then((res) => {
      console.log(res);
      if (res?.data?.result === "000") {
        navigate("/voucher");
      } else {
        alert("입력한 정보를 확인해주세요!");
      }
    });
  }

  const onClickAddVoucher = () => {
    const data = {
      store_voucher_title: voucherName,
      store_voucher_desc: voucherDetail,
      store_voucher_discount_rate: Number(voucherRate),
      store_voucher_available_days: day.join(","),
      store_voucher_beg_date: startDay,
      store_voucher_end_date: endDay,
      store_voucher_beg_time: ChangeTimeData(
        `${dayjs().format("YYYY-MM-DD")}${startTime}`
      ),
      store_voucher_end_time: ChangeTimeData(
        `${dayjs().format("YYYY-MM-DD")}${endTime}`
      ),
      store_voucher_daily_total_cnt: Number(voucherCnt),
    };

    {
      !!state ? postVoucher(storeId, id, data) : putVoucher(storeId, data);
    }
  };

  return (
    <div>
      <NavigationHeader title={"쿠폰등록"} value="back" />
      <AddWrap>
        <div className="inputWrap">
          <span className="title">
            쿠폰명
            <Title color="#f33562" size={20}>
              {" "}
              *
            </Title>
          </span>
          <Input
            placeholder="쿠폰명을 입력해주세요."
            onChange={(e) => setVoucherName(e.target.value)}
            value={voucherName || ""}
          />
        </div>
        <div className="inputWrap">
          <span className="title">상세설명</span>
          <Input
            placeholder="내용을 입력해주세요."
            onChange={(e) => setVoucherDetail(e.target.value)}
            value={voucherDetail || ""}
          />
        </div>
        <div className="inputWrap">
          <span className="title">
            할인이율{" "}
            <Title color="#f33562" size={20}>
              {"  "}*
            </Title>
          </span>

          <Input
            placeholder="예) 30"
            width={30}
            onChange={(e) => setVoucherRate(e.target.value)}
            value={voucherRate || ""}
          />
          <span className="unit"> % </span>
        </div>
        <div className="inputWrap">
          <span className="title">
            이 날 사용할 수있어요{" "}
            <Title color="#f33562" size={20}>
              {"  "}*
            </Title>
          </span>
          <WeekDay day={day} setDay={setDay} />
        </div>
        <div className="inputWrap">
          <span className="title margin">
            운영기간{" "}
            <Title color="#f33562" size={20}>
              {"  "}*
            </Title>
          </span>
          <div className="between">
            <MonthCalendar setDate={setStartDay} date={startDay} />
            <span>-</span>
            <MonthCalendar setDate={setEndDay} date={endDay} />
          </div>
          <div className="between">
            <BasicTimePicker setTime={setStartTime} time={startTime} />
            <span>-</span>
            <BasicTimePicker setTime={setEndTime} time={endTime} />
          </div>
        </div>

        <div className="inputWrap">
          <span className="title">
            하루 발생 수
            <Title color="#f33562" size={20}>
              {"  "}*
            </Title>
          </span>
          <Input
            placeholder="예) 1,000"
            width={50}
            onChange={(e) => setVoucherCnt(e.target.value)}
            value={voucherCnt || ""}
            type="number"
          />
        </div>
      </AddWrap>
      <BottomBtn
        title={!!state ? "수정하기" : "등록하기"}
        onClick={onClickAddVoucher}
      />
    </div>
  );
};

const AddWrap = styled.div`
  padding: 70px 20px 90px 20px;
  box-sizing: border-box;
  & .inputWrap {
    position: relative;
    margin-top: 25px;
  }
  & .title {
    display: flex;
    font-size: 18px;
    font-weight: 600;
  }

  & .margin {
    margin-bottom: 10px;
  }
  & .unit {
    color: #7d7d7d;
    font-size: 14px;
    position: relative;
    left: -25px;
  }

  & .between {
    display: flex;
    align-items: center;
    & .css-4jnixx-MuiStack-root > .MuiTextField-root {
      min-width: 90px;
    }
    &
      .css-9ddj71-MuiInputBase-root-MuiOutlinedInput-root.Mui-error
      .MuiOutlinedInput-notchedOutline {
      border-color: rgba(0, 0, 0, 0.23);
    }
    &:last-child {
      margin-top: 10px;
    }
  }
`;

const Input = styled.input`
  position: relative;
  width: ${(props) => props.width || 100}%;
  border: 1px solid #d7d7d7;
  height: 50px;
  border-radius: 5px;
  margin-top: 8px;
  padding: 0px 25px 0px 20px;
  box-sizing: border-box;
`;

const Button = styled.button`
  position: relative;
  width: 45%;
  border: 1px solid #d7d7d7;
  height: 50px;
  border-radius: 5px;
  margin-top: 8px;
  padding: 0px 25px 0px 20px;
  box-sizing: border-box;

  display: flex;
  justify-content: space-between;
  align-items: center;

  color: ${(props) => props.color || "#d7d7d7"};
  font-size: 15px;
`;

export default AddVoucherPage;
