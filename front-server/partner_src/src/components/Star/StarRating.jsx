import React from "react";
import { Display, Title } from "../../styles/styledComponent";
import { BsStarFill, BsStarHalf } from "react-icons/bs";
import styled from "styled-components";

const StarRating = ({ starData }) => {
  const rating = Number(starData);
  const size = 17;
  return (
    <Star>
      <Display>
        {Array(parseInt(rating))
          ?.fill(2)
          ?.map((el, i) => (
            <div key={i}>
              <BsStarFill size={size} color="#f33562" />
            </div>
          ))}
        {!Number.isInteger(rating) && (
          <div>
            <BsStarHalf size={size} color="red" />
          </div>
        )}
        {Array(Math?.floor(5 - rating))
          ?.fill(2)
          ?.map((el, i) => (
            <div key={i}>
              <BsStarFill size={size} color="#fed0d8" />
            </div>
          ))}
      </Display>
      <Title color="#f33562" left={5}>
        {rating ? `${rating}.0` : rating}
      </Title>
    </Star>
  );
};

const Star = styled(Display)`
  & div {
    margin-right: 2px;
  }
`;

export default StarRating;
