import styled from "styled-components";

export const Container = styled.div`
  width: 100%;
  height: 100%;
  box-sizing: border-box;
`;

export const Img = styled.img`
  width: ${(props) => props.width}px;
  height: ${(props) => props.height || props.width}px;
  border-radius: ${(props) => props.radius || "0"}px;
  margin-right: ${(props) => props.right || "0"}px;
  margin-left: ${(props) => props.left || "0"}px;
  margin-bottom: ${(props) => props.bottom || "0"}px;

  object-fit: ${(props) => props.fit || "contain"};
`;

export const Display = styled.div`
  display: flex;
  justify-content: ${(props) => props.content || "flex-start"};
  align-items: center;
  align-content: center;
  margin-top: ${(props) => props.top || 0}px;
  margin-bottom: ${(props) => props.bottom || 0}px;
`;

export const Title = styled.span`
  font-size: ${(props) => props.size || 17}px;
  color: ${(props) => props.color || "#333"};
  font-weight: ${(props) => props.weight || 500};
  margin-right: ${(props) => props.right || 0}px;
  margin-left: ${(props) => props.left || 0}px;
  margin-top: ${(props) => props.top || 0}px;
  margin-bottom: ${(props) => props.bottom || 0}px;
  /* line-height: 28px; */
`;

export const Btn = styled.button`
  display: flex;
  justify-content: center;
  align-items: center;
  max-width: 500px;
  height: ${(props) => props.height || 60}px;
  border-radius: ${(props) => props.radius || 0}px;
  background-color: ${(props) => props.bg || "#333"};
  color: ${(props) => props.color || "#fff"};
  font-size: ${(props) => props.size || 20}px;
  font-weight: 600;
  z-index: 100;
`;

export const BtnWrap = styled.div`
  width: 100%;
  max-width: 500px;
  display: grid;
  grid-template-columns: repeat(2, 1fr);

  position: fixed;
  bottom: 0px;
  z-index: 10;
`;

export const LoginInput = styled.input`
  background-color: #ececec;
  width: ${(props) => props.width || 100}%;
  height: 55px;
  border-radius: 5px;
  margin-bottom: 15px;
  padding: 0px 18px;
  box-sizing: border-box;
  border: none;

  &:focus {
    outline: none;
  }
`;

export const Input = styled.input`
  width: ${(props) => props.width || 100}%;
  height: 50px;
  border-radius: 5px;
  padding: 15px 20px;
  border-width: 1px;
  border-color: #d7d7d7;
  border-style: solid;
  margin-bottom: ${(props) => props.bottom || 0}px;
  margin-top: ${(props) => props.top || 0}px;
  color: #333;
  box-sizing: border-box;
`;

export const AddTitle = styled(Title)`
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 12px;
  margin-top: 30px;
`;

export const InputBtn = styled.button`
  border-bottom-width: ${(props) => props.border || 0}px;
  border-bottom-color: #888888;
`;

export const Zindex = styled.div`
  z-index: -100;
  margin-bottom: ${(props) => props.bottom || 0}px;
`;

export const Box = styled.button`
  display: flex;
  justify-content: center;
  align-items: center;
  border: 1px solid;
  border-color: ${(props) => props.bordercolor || "#d7d7d7"};
  border-radius: 5px;

  background-color: ${(props) => props.bg || "#fff"};
  margin-right: ${(props) => props.right}px;
  margin-bottom: 10px;
`;
