import React, { useState } from "react";
import styled from "styled-components";
import Toggle from "../Toggle/Toggle";
import apis from "../../api/apis";
import dayjs from "dayjs";
import { Title, Img } from "../../styles/styledComponent";
import IconProfile from "../../assets/image/profile.png";
import StarRating from "../Star/StarRating";
import ReviewInfo from "./ReviewInfo";
import { useNavigate } from "react-router-dom";

const ReivewCard = ({ data, id }) => {
  const navigation = useNavigate();
  const block = data?.review_block_yn === "n";
  const [isOn, setIson] = useState(block);

  const toggleSwitch = (e, idx) => {
    const eidtData = { review_block_yn: !e ? "y" : "n" };
    apis.postReivew(id, idx, eidtData).then((res) => {
      if (res.data.result === "000") {
        setIson(!isOn);
      }
    });
  };

  const answerReg = dayjs().format("YY.MM.DD");

  return (
    <Card>
      <div className="top">
        <Title size={18} color="#444">
          {isOn ? "공개" : "비공개"}
        </Title>
        <Toggle isOn={isOn} toggleSwitch={toggleSwitch} id={data?.review_idx} />
      </div>
      <div className="infoWrap">
        <Img
          src={!!data?.mb_profile_img ? data?.mb_profile_img : IconProfile}
          width={70}
          fit="cover"
          radius={100}
        />
        <div className="profile">
          <Title size={18} color="#444" bottom={5}>
            {data?.mb_nickname}
          </Title>
          <StarRating starData={data?.review_rating} />
        </div>
        <div className="day">
          <Title size={14} color="#7d7d7d">
            {dayjs(data.review_reg_dt).format("YY.MM.DD")}
          </Title>
        </div>
      </div>
      <div className="padding">
        <ReviewInfo data={data} />
      </div>

      <div className="padding">
        {!data.review_answer ? (
          <button
            className="btn"
            onClick={() =>
              navigation(`/review/${data.review_idx}`, { state: id })
            }
          >
            <Title color="#f33562">답변달기</Title>
          </button>
        ) : (
          <Answer>
            <div className="answer">
              <div className="title">
                <Title color="#fff" size={15}>
                  답변
                </Title>
              </div>
              <Title color="#444" size={15} left={5}>
                {answerReg}
              </Title>
            </div>
            <Title color="#444" size={15} top={10}>
              {data.review_answer}
            </Title>
          </Answer>
        )}
      </div>
    </Card>
  );
};

const Card = styled.div`
  border: 1px solid #efefef;
  border-radius: 5px;
  margin-bottom: 20px;
  padding-bottom: 10px;
  box-shadow: 2px 1px 1px 1px rgb(239, 239, 239, 0.3);

  & .top {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    height: 40px;
    background-color: #e9e9e9;
    padding: 0 10px;
  }

  & .infoWrap {
    position: relative;
    display: flex;
    align-items: center;
    padding: 10px 15px;
    & .day {
      position: absolute;
      right: 10px;
      top: 20px;
    }
  }

  & .profile {
    display: flex;
    flex-direction: column;
    margin-left: 10px;
  }

  & .padding {
    padding: 0 10px;
    & .btn {
      width: 100%;
      height: 40px;
      border: 1px solid #f33562;
      border-radius: 5px;
      display: flex;
      align-items: center;
      justify-content: center;

      margin-top: 20px;
    }
  }
`;

const Answer = styled.div`
  background-color: #e9e9e9;
  padding: 20px 15px;
  margin-top: 20px;

  & .answer {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
  }

  & .title {
    background-color: #575757;
    border-radius: 7px;
    width: 40px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
`;
export default ReivewCard;
