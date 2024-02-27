import React, { useEffect, useState } from "react";

import NavigationHeader from "../components/Header/NavigationHeader";
import apis from "../api/apis";
import NavBar from "../components/NavBar/NavBar";
import ReivewCard from "../components/Review/ReivewCard";
import styled from "styled-components";
import { Display } from "../styles/styledComponent";
const ReviewPage = () => {
  const storeId = localStorage.getItem("storeId");
  const navData = [
    { title: "전체" },
    { title: "포토리뷰" },
    { title: "댓글쓰기" },
  ];
  const [list, setList] = useState();
  const [reviewList, setReivewList] = useState();
  const [imgList, setImgList] = useState();
  const [answerList, setAnswerList] = useState();
  const [navValue, setNavValue] = useState(0);

  useEffect(() => {
    apis.getReviewList(storeId).then((res) => {
      const data = res.data.list;
      const img = data?.filter((el) => el.review_img1 !== "");
      const answer = data?.filter((el) => el.review_answer == "");
      setImgList(img);
      setAnswerList(answer);
      setList(data);
      setReivewList(data);
    });
  }, []);

  const onClickNav = (k) => {
    setNavValue(k);
    k === 1
      ? setList(imgList)
      : k === 2
      ? setList(answerList)
      : setList(reviewList);
  };
  function arrLength(index) {
    switch (index) {
      case 0:
        return !list ? 0 : reviewList?.length;
      case 1:
        return !list ? 0 : imgList?.length;
      case 2:
        return !list ? 0 : answerList?.length;
    }
  }

  return (
    <Wrap>
      <NavigationHeader title={"후기 리스트"} />
      <NavBar
        navData={navData}
        onClickNav={onClickNav}
        navValue={navValue}
        arrLength={arrLength}
        screen={"review"}
      />
      <div className="cards">
        {list &&
          list.map((i, k) => {
            return (
              <div key={k}>
                <ReivewCard data={i} id={storeId} />
              </div>
            );
          })}
      </div>
    </Wrap>
  );
};

const Wrap = styled.div`
  padding-top: 120px;
  & .cards {
    padding: 20px;
    height: calc(100vh - 120px);
  }
`;

export default ReviewPage;
