import React, { useState } from "react";
import { styled } from "styled-components";
import { useNavigate } from "react-router-dom";
import { Title, Img, Display } from "../../styles/styledComponent";
import IconMore from "../../assets/image/more.png";
import { Hour, Minute } from "../../utils/TimeCalculation";
import dayjs from "dayjs";
import CheckBox from "../CheckBox/CheckBox";
import { MakeWeekFun } from "../../utils/MakeWeekFun";

const VoucherCard = ({ data, deleteBtn, onClickCheck, checkValue }) => {
  const navigate = useNavigate();
  const [open, setOpen] = useState(false);

  const use = data?.store_voucher_use_yn === "y";
  const begtime = data.store_voucher_beg_time;
  const endtime = data.store_voucher_end_time;

  function timeSetting(time) {
    const hour = Hour(time);
    const minute = Minute(time);
    if (hour < 12) {
      return minute === "00" ? `오전${hour}시` : `오전${hour}시${minute}분`;
    } else {
      return minute === "00" ? `오후${hour}시` : `오후${hour}시${minute}분`;
    }
  }
  function dayjsFormat(data) {
    return dayjs(data).format("YY.MM.DD");
  }

  return (
    <Card>
      {deleteBtn ? (
        <Position>
          <CheckBox
            isCheck={checkValue}
            onClickCheck={onClickCheck}
            id={data?.store_voucher_idx}
          />
        </Position>
      ) : (
        ""
      )}
      <Display content="space-between">
        <SttBox bg={use ? "#EBF3FF" : "#efefef"}>
          <Title color={use ? "#4E95FF" : "#7d7d7d"} size={15}>
            {use ? "노출중" : "비노출"}
          </Title>
        </SttBox>
        <button
          onClick={() =>
            navigate(`/eidtvoucher/${data?.store_voucher_idx}`, { state: data })
          }
        >
          <Img src={IconMore} width={20} />
        </button>
      </Display>
      <Title size={27} color="#f33562" weight={600} top={3}>
        {data?.store_voucher_discount_rate}% <Title color="#f33562">할인</Title>
      </Title>

      <div className="info">
        <Title top={15} bottom={10} color="#333" weight={700}>
          {data.store_voucher_title}
        </Title>
        <Info>
          적용기간 : {dayjsFormat(data.store_voucher_beg_date)} -{" "}
          {dayjsFormat(data.store_voucher_end_date)}
        </Info>
        <Info>
          사용시간 : {timeSetting(begtime)}부터 ~ {timeSetting(endtime)}까지
        </Info>
        <Info>하루 발행 수 : {data.store_voucher_daily_total_cnt}장</Info>
        <Info>
          이용 가능 요일 : {MakeWeekFun(data?.store_voucher_available_days)}
        </Info>
      </div>
    </Card>
  );
};

const Card = styled.div`
  position: relative;
  border: 1px solid #e9e9e9;
  border-radius: 5px;
  margin-bottom: 15px;
  padding: 15px 20px;

  & .info {
    display: flex;
    flex-direction: column;
  }
`;

const SttBox = styled.div`
  display: inline;
  padding: 5px 6px;
  border-radius: 4px;
  background-color: ${(props) => props.bg};

  align-items: center;
  justify-content: center;
  margin-bottom: 5px;
`;

const Info = styled.span`
  font-size: 13px;
  color: #7d7d7d;
  margin-bottom: 5px;
`;

const Position = styled.div`
  position: absolute;
  left: -10px;
  top: -10px;
  z-index: 100;
`;

export default VoucherCard;
