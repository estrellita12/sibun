import React from "react";

import { styled } from "styled-components";
import { Title } from "../../styles/styledComponent";
const StoreListHeader = ({ onClickEdit, edit }) => {
  return (
    <HeaderWrap>
      <Title color="#222" weight="600" size="20">
        매장선택
      </Title>
      <Btn onClick={() => onClickEdit()}>
        <Title color="#444" weight="400" size="14">
          {edit ? "취소" : "편집"}
        </Title>
      </Btn>
    </HeaderWrap>
  );
};

const HeaderWrap = styled.div`
  display: flex;
`;

const Btn = styled.button`
  position: absolute;
  right: 0px;
`;
export default StoreListHeader;
