import React from "react";
import { Title } from "../../styles/styledComponent";
import { styled } from "styled-components";
const Dropdown = ({ data, width, setData, getData, onClickDown, align }) => {
  const onClickItem = (data) => {
    getData("store_pricing_days", data?.value);
    setData(data?.title);
    onClickDown();
  };

  return (
    <DropDown width={width}>
      {data?.map((i, k) => {
        return (
          <Item
            align={align}
            key={k}
            border={k + 1 === data?.length ? 0 : 1}
            onClick={() => onClickItem(i)}
          >
            <Title size={14}>{i.title}</Title>
          </Item>
        );
      })}
    </DropDown>
  );
};

const DropDown = styled.div`
  width: ${(props) => props.width}%;
  background-color: #ffffff;
  border: 1px solid #d7d7d7;
  border-radius: 5px;
  margin-top: 4px;
  z-index: 1000;
  box-sizing: border-box;
`;

const Item = styled.button`
  display: block;
  width: 100%;
  height: 50px;
  padding: 5px 15px;

  border-bottom-width: ${(props) => props.border}px;
  border-color: #d7d7d7;
  box-sizing: border-box;
`;

export default Dropdown;
