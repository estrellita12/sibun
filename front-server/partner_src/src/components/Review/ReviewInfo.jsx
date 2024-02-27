import React from "react";

import { Display } from "../../styles/styledComponent";
import { styled } from "styled-components";

const ReviewInfo = ({ data }) => {
  const tags = !data.review_tags ? false : data.review_tags.split(",");
  const reviewImgList = [data.review_img1, data.review_img2, data.review_img3];
  const reviewImg = reviewImgList.filter((el) => el !== "");

  return (
    <Info>
      <Display
        top={10}
        content={reviewImg?.length === 3 ? "space-between" : "flex-start"}
      >
        {reviewImg &&
          reviewImg?.map((i, k) => {
            return <ReviewImg src={i} width={32} radius={5} key={k} />;
          })}
      </Display>

      {tags ? (
        <div className="flex">
          <Tag>
            {tags?.map((tag, k) => {
              return (
                <span className="tag" key={k}>
                  #{tag}
                </span>
              );
            })}
          </Tag>
          <span size={18} color="#222">
            {data.review_content}
          </span>
        </div>
      ) : (
        ""
      )}
    </Info>
  );
};
const Info = styled.div`
  & .flex {
    display: inline-flex;
    flex-direction: column;
  }
`;

const Tag = styled.div`
  margin-top: 20px;
  margin-bottom: 10px;
  background-color: #e9e9e9;
  padding: 5px 10px;

  & .tag {
    margin-right: 7px;
    font-size: 15px;
    &:last-child {
      margin-right: 0px;
    }
  }
`;

const ReviewImg = styled.img`
  width: ${(props) => props.width}%;
  height: 100px;
  border-radius: 5px;
  margin-right: 8px;
`;

export default ReviewInfo;
