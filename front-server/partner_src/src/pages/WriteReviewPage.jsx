import React, { useEffect, useState } from "react";
import { useLocation, useNavigate, useParams } from "react-router-dom";
import styled from "styled-components";
import dayjs from "dayjs";

import NavigationHeader from "../components/Header/NavigationHeader";
import StarRating from "../components/Star/StarRating";
import apis from "../api/apis";
import { Img, Title, BtnWrap, Btn } from "../styles/styledComponent";
import IconProfile from "../assets/image/profile.png";
import ReviewInfo from "../components/Review/ReviewInfo";

const WriteReviewPage = () => {
  const navigate = useNavigate();
  const { id } = useParams();
  const { state } = useLocation();
  const [data, setData] = useState();
  const [answer, setAnswer] = useState(0);

  useEffect(() => {
    apis.getReview(state, id).then((res) => {
      if (res.data.result === "000") {
        setData(res.data.data);
      }
    });
  }, []);

  const onChangeAnswer = (e) => {
    setAnswer(e);
  };

  const onClickAdd = () => {
    const data = { review_answer: answer };
    apis.postReivew(state, id, data).then((res) => {
      if (res.data.result === "000") {
        alert("댓글 작성이 완료되었습니다.");
        setTimeout(() => navigate("/review"), 1000);
      }
    });
  };

  return (
    <Write>
      <NavigationHeader value={"back"} title="답변달기" />
      {data && (
        <div>
          <div className="top">
            <div className="imgWrap">
              <Img
                src={
                  !!data?.mb_profile_img ? data?.mb_profile_img : IconProfile
                }
                width={70}
                fit="cover"
                radius={100}
              />
              <Title size={18} color="#222" weight={400} left={10}>
                {data?.mb_nickname}
              </Title>
            </div>
            <div className="infoWrap">
              <StarRating starData={data?.review_rating} />
              <Title size={14} color="#7d7d7d" weight={400}>
                {dayjs(data?.review_reg_dt).format("YY.MM.DD")}
              </Title>
            </div>
            <ReviewInfo data={data} />
          </div>
          <div className="bottom">
            <div>
              <p>고객님 리뷰에 댓글을 달아주세요.</p>
              <textarea
                onChange={(e) => onChangeAnswer(e.target.value)}
                placeholder="신속한 답변으로 고객관리를 해주세요."
              />
            </div>
          </div>
        </div>
      )}

      <BtnWrap>
        <Btn bg={"#D7D7D7"} color={"#444"} onClick={() => navigate("/review")}>
          취소
        </Btn>
        <Btn
          bg={answer?.length > 10 ? "#f33562" : "#333"}
          onClick={() => onClickAdd()}
        >
          등록
        </Btn>
      </BtnWrap>
    </Write>
  );
};

const Write = styled.div`
  padding-bottom: 70px;
  & .top {
    width: 90%;
    margin: auto;
    & .imgWrap {
      display: flex;
      flex-direction: row;
      align-items: center;
      height: 120px;
      border-bottom: 1px solid #e9e9e9;
    }
    & .infoWrap {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }
  }

  & .bottom {
    margin-top: 30px;
    border-top: 10px solid #e9e9e9;
    padding: 0px 20px;

    & p {
      font-size: 18px;
    }

    & textarea {
      width: 100%;
      height: 150px;
      border: 1px solid #e9e9e9;
      border-radius: 5px;
      outline-color: none;
      padding: 20px;
      box-sizing: border-box;
      resize: none;
    }
  }
`;

export default WriteReviewPage;
